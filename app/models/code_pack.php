<?php

class CodePack extends AppModel
{
    //書き込み権限があるかどうか
    public $writable = false;

    public $user = null;

    /**
     * $pathで指定されるCodePackを取得する
     * 取得したCodePackが$userのものだった場合書き込み権限が与えられる
     */
    public static function get(User $user, $path)
    {
        $db = DB::conn();

        $row = $db->row('SELECT * FROM code_pack WHERE path = ?', array($path));
        if (!$row) {
            throw new RecordNotFoundException();
        }

        if ($user->id == $row['user_id']) {
            $row['writable'] = true;
        }

        $row['user'] = $user;

        return new self($row);
    }

    /**
     * $userが持つ全てのCodePackを取得する
     * 書き込み権限は全て有効になる
     */
    public static function getAll(User $user)
    {
        $code_packs = array();

        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM code_pack WHERE user_id = ?', array($user->id));
        foreach ($rows as $row) {
            $row['user'] = $user;
            $row['writable'] = true;
            $code_packs[] = new self($row);
        }

        return $code_packs;
    }

    /**
     * 新規にコードパックを作成する
     * 書き込み権限は有効になる
     */
    public static function create(User $user, $title, $description, $codes_param)
    {
        $db = DB::conn();

        $db->begin();

        //一意のpathを決定する
        while (true) {
            $path = randomString(16);
            $row = $db->row('SELECT * FROM code_pack WHERE path = ?', array($path));
            if (!$row) {
                break;
            }
        }

        $params = array(
            'user_id' => $user->id,
            'path' => $path,
            'title' => $title,
            'description' => $description,
            'created' => Time::now()
        );

        $db->insert('code_pack', $params);

        $params['id'] = $db->lastInsertId();
        $params['user'] = $user;
        $params['writable'] = true;
        $code_pack =  new self($params);

        foreach ($codes_param as $v) {
            $code_pack->add($v['class'], $v['code']);
        }

        $db->commit();

        return $code_pack;
    }

    /**
     * 新しくCodeを追加する
     */
    public function add($class, $code)
    {
        $this->checkPermission();

        if (!$code) {
            return;
        }

        $db = DB::conn();

        $params = array(
            'user_id' => $this->user->id,
            'code_pack_id' => $this->id,
            'class' => $class,
            'code' => $code
        );

        $db->insert('code', $params);
    }

    /**
     * 全てのCodeを取得する
     */
    public function getCodes()
    {
        return Code::getAll($this->user, $this->id);
    }

    /**
     * CodePack に紐付く特定の Code を取得する
     */
    public function getCode($code_id)
    {
        return Code::get($this->user, $code_id);
    }

    /**
     * [{id,class,code}, ...]を受け取ってコードを更新する
     * idがnullのものは新規作成とする
     */
    public function updateCodes($codes)
    {
        $this->checkPermission();

        $db = DB::conn();

        $db->begin();
        foreach ($codes as $v) {
            $id = $v['id'];
            $class = $v['class'];
            $code = $v['code'];
            if ($id) {
                if ($code) {
                    $db->query('UPDATE code SET class = ?, code = ? WHERE id = ? AND user_id = ?', array($class, $code, $id, $this->user->id));
                } else {
                    //更新時にコードが空の場合は削除とみなす
                    $db->query('DELETE FROM code WHERE id = ? AND user_id = ?', array($id, $this->user->id));
                }
            }
            else{
                $this->add($class, $code);
            }
        }

        $db->commit();
    }

    /**
     * CodePackを更新する
     */
    public function update($title, $description)
    {
        $this->checkPermission();

        $db = DB::conn();

        $db->query('UPDATE code_pack SET title = ?, description = ? WHERE id = ?', array($title, $description, $this->id));
    }

    /**
     * CodePackと関連づくCodeを全て削除する
     */
    public function delete()
    {
        $this->checkPermission();

        $db = DB::conn();

        $db->begin();

        $db->query('DELETE FROM code WHERE code_pack_id = ? AND user_id = ?', array($this->id, $this->user->id));
        $db->query('DELETE FROM code_pack WHERE id = ? AND user_id = ?', array($this->id, $this->user->id));

        $db->commit();

    }

    /**
     * 書き込み権限があるか確認する
     */
    public function checkPermission()
    {
        if (!$this->writable) {
            throw new PermissionDeniedException();
        }
    }

    public function copyTo(User $me)
    {
        if ($this->writable) {
            throw new InvalidArgumentException('this code is yours!');
        }

        $codes_param = array();
        foreach ($this->getCodes() as $code) {
            $codes_param[] = array(
                'class' => $code->class,
                'code' => $code->code,
            );
        }
        CodePack::create($me, $this->title, $this->description, $codes_param);
    }
}
