<?php

class DB
{

    public static $instance_id;     // DB のインスタンスを区別するための ID（デバッグ用）
    public static $log_sql = false; // SQL のログを出力するかどうか
    public static $num_query = 0;   // 実行したクエリーのカウンタ（ログ用）
    public $database;       // 現在データベース名
    public $pdo = null;     // PDO インスタンス
    protected $st = null;   // PDO ステートメント
    protected $trans_stack = array();   // トランザクションのネストを管理する

    protected function __construct($database, $dsn, $username, $password, $driver_options)
    {
        $this->database = $database;
        $this->pdo = new PDO($dsn, $username, $password, $driver_options);

        // エラー時には例外を投げるように設定
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::$instance_id = microtime(true); // デバッグ用に PDO のインスタンスに id を振る
    }

    /**
     * データベース接続のインスタンスを取得する
     *
     * @return DB
     */
    public static function conn($destination = null, $driver_options = array())
    {
        static $obj;

        $database = DB_NAME;
        $dsn = DB_DSN;
        $username = DB_USERNAME;
        $password = DB_PASSWORD;
        $driver_options = array_merge(array(PDO::ATTR_TIMEOUT => DB_ATTR_TIMEOUT), $driver_options);

        if (isset($obj[$database]))
            return $obj[$database];
        $obj[$database] = new self($database, $dsn, $username, $password, $driver_options);
        return $obj[$database];
    }

    /**
     * SQL が SELECT 文 または SHOW 文かどうか判定する
     *
     * 結果を取得する必要があるクエリーかどうか判定するために使う。
     * 
     * @return boolean
     */
    public function isSelectQuery($sql)
    {
        // trimするのは、sql の先頭に空白が入っている場合に対応するため
        if (stripos(ltrim($sql), 'SELECT') === 0)
            return true;
        if (stripos(ltrim($sql), 'SHOW') === 0)
            return true;
        return false;
    }

    /**
     * IN 句の中身を生成する
     *
     * @return string IN 句の中身
     */
    public function in($params)
    {
        if (empty($params))
            return '';
        $in = '(';
        foreach ($params as $k => $v) {
            $params[$k] = $this->pdo->quote($v);
        }
        $in .= implode(',', $params);
        $in .= ')';
        return $in;
    }

    /**
     * SQL を実行する。戻り値なし
     *
     */
    public function query($sql, array $params = array())
    {
        $this->st = $this->pdo->prepare($sql);
        $this->log($sql, $params);
        $this->st->execute($params);
    }

    /**
     * SQL を実行する。結果は単一行で返る
     *
     * @return array|boolean SELECT 文のとき、取得結果を配列で返す。結果が見つからなかったとき false を返す
     */
    public function row($sql, array $params = array())
    {
        $this->st = $this->pdo->prepare($sql);
        $this->log($sql, $params);
        $r = $this->st->execute($params);
        if (!$this->isSelectQuery($sql))
            return $r;
        return $this->st->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * SQL を実行する。結果は複数行で返る
     *
     * @return array SELECT 文のとき、取得結果を配列で返す。結果が見つからなかったとき空配列を返す
     */
    public function rows($sql, array $params = array())
    {
        $this->st = $this->pdo->prepare($sql);
        $this->log($sql, $params);
        $r = $this->st->execute($params);
        if (!$this->isSelectQuery($sql))
            return $r;
        $rows = $this->st->fetchAll(PDO::FETCH_ASSOC);
        return $rows ? $rows : array();
    }

    /**
     * SQL を実行する。結果は単一行で、値のみ返る
     *
     * @return mixed
     */
    public function value($sql, array $params = array())
    {
        $row = $this->row($sql, $params);
        return $row ? current($row) : false;
    }

    /**
     * 単純な INSERT 文を実行する
     *
     */
    public function insert($table, array $params)
    {
        $cols = implode(', ', array_keys($params));
        $placeholders = implode(', ', str_split(str_repeat('?', count($params))));
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $cols, $placeholders);
        $this->query($sql, array_values($params));
    }

    /**
     * 単純な REPLACE 文を実行する
     *
     */
    public function replace($table, $params)
    {
        $cols = implode(', ', array_keys($params));
        $placeholders = implode(', ', str_split(str_repeat('?', count($params))));
        $sql = sprintf('REPLACE INTO %s (%s) VALUES (%s)', $table, $cols, $placeholders);
        $this->query($sql, array_values($params));
    }

    /**
     * 単純な UPDATE 文を実行する
     *
     */
    public function update($table, $params, $where_params)
    {
        // 対象のカラム名と値
        $pairs = '';
        foreach ($params as $k => $v) {
            $pairs .= $k . ' = ?, ';
        }
        $pairs = substr($pairs, 0, -2);

        // WHERE 句
        $where = '';
        foreach ($where_params as $k => $v) {
            $where .= $k . ' = ? AND ';
        }
        $where = substr($where, 0, -5);

        $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $pairs, $where);
        $this->query($sql, array_merge(array_values($params), array_values($where_params)));
    }

    /**
     * 単純な検索クエリーを実行する
     *
     * 使用例：
     * $this->search('item', 'id BETWEEN ? AND ?', array(1000, 1999), 'id DESC', array(1, 10));
     *
     * @param string $table 対象のテーブル名
     * @param string $where WHERE 句
     * @param array $params 束縛するパラメータ
     * @param string $order ORDER 句（オプション）
     * @param string $limit LIMIT 句（オプション）
     * @param array $options その他のオプション
     *                       select_expr キー：SELECT で取り出すカラム。デフォルトは * （全カラム）
     * @return array 取得結果を配列で返す。結果が見つからなかったとき空配列を返す
     */
    public function search($table, $where, $params, $order = null,
            $limit = null, $options = array())
    {
        // SELECT で取り出すカラムを指定
        $select_expr = '*';
        if (isset($options['select_expr'])) {
            $select_expr = $options['select_expr'];
        }

        // SQL 文の組み立て
        $sql = sprintf('SELECT %s FROM %s WHERE %s', $select_expr, $table, $where);

        // ORDER 句
        if (!is_null($order)) {
            $sql .= sprintf(' ORDER BY %s', $order);
        }

        // LIMIT 句
        if (!is_null($limit)) {
            if (is_array($limit)) {
                $limit = implode(', ', $limit);
            }
            $sql .= sprintf(' LIMIT %s', $limit);
        }

        return $this->rows($sql, $params);
    }

    /**
     * トランザクションを開始する
     *
     * @return boolean トランザクションが開始されたとき true、失敗したときまたは既に開始中のときは false
     */
    public function begin()
    {
        if (count($this->trans_stack) > 0) {
            array_push($this->trans_stack, 'A');
            return false;
        }

        $r = $this->pdo->beginTransaction();
        if ($r) {
            $this->log('BEGIN');
            array_push($this->trans_stack, 'A');
        }

        return $r;
    }

    /**
     * トランザクションをコミットする
     *
     * @return boolean トランザクションがコミットされたとき true、失敗したときまたはネストしているとき false
     */
    public function commit()
    {
        if (count($this->trans_stack) > 1) {
            array_pop($this->trans_stack);
            return false;
        }

        $r = $this->pdo->commit();
        if ($r) {
            $this->log('COMMIT');
            array_pop($this->trans_stack);
        }

        return $r;
    }

    /**
     * トランザクションをロールバックする
     *
     * @return boolean トランザクションがコミットされたとき true、失敗したときまたはネストしているとき false
     */
    public function rollback()
    {
        if (count($this->trans_stack) > 1) {
            array_pop($this->trans_stack);
            return false;
        }

        $r = $this->pdo->rollback();
        if ($r) {
            $this->log('ROLLBACK');
            array_pop($this->trans_stack);
        }

        return $r;
    }

    /**
     * 最後に INSERT した行の ID を返す
     *
     */
    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * 直近の SQL ステートメントで作用した行数を返す
     *
     */
    public function rowCount()
    {
        return $this->st->rowCount();
    }

    /**
     * 実行する SQL のログを書き込む
     *
     */
    protected function log($sql, array $params = array())
    {
        if (!self::$log_sql)
            return;

        static $ts = null;
        if (is_null($ts)) {
            $ts = microtime(true);
        }
        $t = microtime(true) - $ts;

        Log::info(sprintf("sql\t%f\t%d:(%s) %s; (%s)", $t, self::$num_query++, $this->database, $sql, implode(', ', $params)), 'sql');
    }

}
