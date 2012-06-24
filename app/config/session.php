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

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }
}
