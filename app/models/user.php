<?php

class User extends AppModel
{
    /**
     * $idからUserオブジェクトを取得する
     */
    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE id = ?', array($id));
        if (!$row) {
            throw new RecoredNotFoundException();
        }

        return new self($row);
    }

    /**
     * ユーザを新規作成する
     * 既に作成されている場合は作成しない
     */
    public static function create($name, $access_token)
    {
        $db = DB::conn();

        $db->begin();

        $row = $db->row('SELECT * FROM user WHERE name = ?', array($name));
        if (!$row) {
            $id = self::generateId();
            $params = array(
                'id' => $id,
                'name' => $name,
                'access_token' => $access_token,
                'created' => Time::now()
                ); 
            $db->insert('user', $params);
            $row = $params;
        }

        $user = new self($row);

        $db->commit();

        return $user;
    }

    /**
     * ユーザを一意に識別するためのIDを生成する
     */
    public static function generateId()
    {
        $db = DB::conn();

        // ユニークな ID を登録する
        while (true) {
            $id = (int)mt_rand(100000000, 999999999);
            $row = $db->row('SELECT * FROM user WHERE id = ?', array($id));
            if (!$row) {
                break;
            }
        }

        return $id;
    }
}
