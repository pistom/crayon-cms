<?php

namespace CrayonBo;

use CrayonBo\manager\CrayonBoManager;

class CrayonBo
{

    protected $userRole;
    protected $settings;
    protected $manager;
    public $twig;

    function __construct()
    {
        $this->loadConfig();
        session_start();
        $v = new Verification($this->settings['main_dir']);
        $verification = $v->verify();
        $this->userRole = $verification['userRole'];
        $this->userName = $verification['userName'];
        $this->loadTwig();
        $this->manager = new CrayonBoManager();
    }

    protected function loadTwig()
    {
        $loader = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($loader, array('cache' => false, 'debug' => $this->settings['twig_debug']));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addGlobal('settings', $this->settings);
        $this->twig->addGlobal('userPermissions', $this->getUserPermissions());
        $this->twig->addGlobal('userIsAdmin', $this->isAdmin());
        $this->twig->addGlobal('root', $this->settings['main_dir']);
        return $this->twig;
    }

    protected function systemIsInstalled()
    {
        return file_exists('../_install.php') ? false : true;
    }

    public function isAdmin()
    {
        return ($this->userRole == 'admin') ? true : false;
    }

    private function getUserPermissions()
    {
        $json_data = file_get_contents('../data/users.json');
        $users = json_decode($json_data, true);
        return (array_key_exists('permissions', $users[$this->userName])) ? $users[$this->userName]['permissions'] : array();
    }

    public function dieIfUserNotAllowed($resource)
    {
        if (!$this->isAdmin()) {
            if (!in_array($resource, $this->getUserPermissions())){
                die("Not allowed");
            }
        }
    }

    public function getUserRole()
    {
        return $this->userRole;
    }

    public function getManager()
    {
        return $this->manager;
    }

    protected function loadConfig()
    {
        $json_data = file_get_contents('../data/config.json');
        $this->settings = json_decode($json_data, true);
        if ($this->settings['dev_mode']) {
            $this->settings['twig_cache'] = false;
            $this->settings['twig_debug'] = true;
        } else {
            $this->settings['twig_cache'] = 'cache';
            $this->settings['twig_debug'] = false;
        }
    }

    public function getConfig()
    {
        return $this->settings;
    }

    public function saveConfig($settings)
    {
        copy('../data/config.json', '../data/tmp/config-tmp-' . date('YmdHis') . '.json');
        $fp = fopen('../data/config.json', 'w');
        fwrite($fp, json_encode($settings));
        fclose($fp);
    }

    public static function isAjaxRequest()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ? true : false;
    }
}