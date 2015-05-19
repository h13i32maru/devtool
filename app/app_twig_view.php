<?php
require_once VENDOR_DIR . 'twig/twig/lib/Twig/Autoloader.php';
define('TWIG_EXT', '.tpl');

class AppTwigView
{
    public $controller;
    public $vars = array();
    public $twig;

    public function __construct($controller)
    {
        $this->controller = $controller;

        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(VIEWS_DIR);
        $this->twig = new Twig_Environment($loader, array('cache' => TWIG_CACHE_DIR, 'auto_reload' => true));
        $this->twig->addFunction('url', new Twig_Function_Function('url'));
        /*
        $this->twig->addFunction('defined', new Twig_Function_Function('defined'));
        $this->twig->addFunction('round', new Twig_Function_Function('round'));
        $this->twig->addFunction('microtime', new Twig_Function_Function('microtime'));
        $this->twig->addFunction('master', new Twig_Function_Function('master'));
        $this->twig->addFunction('player', new Twig_Function_Function('player'));
        $this->twig->addFunction('img_src', new Twig_Function_Function('img_src'));
        $this->twig->addFunction('file_src', new Twig_Function_Function('file_src'));
        $this->twig->addFunction('calendar', new Twig_Function_Function('calendar'));
        */
    }

    public function render($action = null)
    {
        $action = is_null($action) ? $this->controller->action : $action;
        if (strpos($action, '/') === false) {
            $tpl_file = $this->controller->name . '/' . $action . TWIG_EXT;
        } else {
            $tpl_file = $action . TWIG_EXT;
        }

        $tpl = $this->twig->loadTemplate($tpl_file);

        ob_start();
        $tpl->display($this->vars);
        $out = ob_get_clean();

        $this->controller->output = $out;
    }
}

