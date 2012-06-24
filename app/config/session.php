<?php

session_start();
class Session
{
    public static function setId($id)
    {
        session_regenerate_id();

        $_SESSION['id'] = $id;
    }

    public static function getId()
    {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }

    public static function unsetId()
    {
        unset($_SESSION['id']);
    }
}
