<?php
// autoload
require_once VENDOR_DIR.'autoload.php';

// application
require_once APP_DIR.'app_controller.php';
require_once APP_DIR.'app_model.php';
require_once APP_DIR.'app_twig_view.php';
require_once APP_DIR.'app_exception.php';

// config
require_once CONFIG_DIR.'database.php';
require_once CONFIG_DIR.'router.php';
require_once CONFIG_DIR.'log.php';
require_once CONFIG_DIR.'session.php';

// helper
require_once HELPERS_DIR.'util_helper.php';

// constants
define('WEBROOT_DIR', APP_DIR.'webroot/');
define('IMG_DIR', WEBROOT_DIR.'img/');

// オートロード
// パフォーマンス向上のため、使用するクラスのみ動的に require_once する
spl_autoload_register(
    function($name)
    {
        $filename = Inflector::underscore($name) . '.php';
        if (strpos($name, 'Controller') !== false) {
            require CONTROLLERS_DIR . $filename;
        } elseif (strpos($name, 'Master') !== false) {
            require MASTER_DIR . $filename;
        } else {
            if (file_exists(MODELS_DIR . $filename)) {
                require MODELS_DIR . $filename;
            }
        }
    }
);

