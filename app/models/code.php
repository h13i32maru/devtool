<?php

class Code extends AppModel
{
    public $user = null;

    /**
     * $code_pack_idに紐付く全てのCodeを取得する
     */
    public static function getAll(User $user, $code_pack_id)
    {
        $codes = array();

        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM code WHERE code_pack_id = ?', array($code_pack_id));
        foreach ($rows as $row) {
            $row['user'] = $user;
            $codes[] = new self($row);
        }

        return $codes;
    }
}
