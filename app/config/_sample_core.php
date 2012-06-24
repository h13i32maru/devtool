<?php
// 実行環境
define('APP_HOST', 'devtool.example.com');
define('APP_BASE_PATH', '/');
define('APP_URL', 'http://' . APP_HOST . '/');

define('DB_HOST', 'db.devtool.example.com');
define('DB_NAME', 'devtool');
define('DB_DSN', 'mysql:host='.DB_HOST.';dbname='.DB_NAME);
define('DB_USERNAME', 'devtool_db_username');
define('DB_PASSWORD', 'devtool_db_password');
define('DB_ATTR_TIMEOUT', 3);

Time::set(time());

// デバッグ定数
#define('DEBUG_DUMP_EXCEPTION', true); // unexpected な例外が発生したときに dump 出力する

ini_set('error_log', LOGS_DIR.'php.log');

// ログ出力制御
Log::$file = LOGS_DIR . 'debug.log';
Log::$write_log = true;

// Twig
define('TWIG_CACHE_DIR', TMP_DIR.'twig');
