<?php
class Log
{
    public static $file;                    // 出力先ファイルのパス
    public static $write_log = false;       // エラーログをファイルに出力するかどうか

    public static $log = '';

    public static function info($msg)
    {
        if (!self::$write_log) {
            return;
        }

        if (!is_string($msg)) {
            ob_start();
            ini_set('html_errors', 0);
            var_dump($msg);
            ini_set('html_errors', 1);
            $msg = ob_get_clean();
        }
        $log = sprintf("%s\t%s\n", date('Y-m-d\TH:i:s'), $msg);
        if (self::$write_log) {
            file_put_contents(self::$file, $log, FILE_APPEND | LOCK_EX);
        }
        self::$log .= $log;
    }
}

