<?php
namespace CrayonBo;

class CrayonBo {

    protected $userRole;
    protected $settings;
    protected $manager;
    public $twig;

    function __construct() {
        if($this->systemIsInstalled()){
            $this->loadConfig();
            session_start();
            include_once 'verification.php';
            require_once 'app/manager/CrayonBoManager.php';
            $this->userRole = $userRole;
            $this->loadTwig();
            $this->manager = new \CrayonBoManager();
        } else {
            header('location:../_install.php');
        }
    }

    protected function loadTwig(){
        $loader = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($loader, array('cache' => false,'debug' => $this->settings['twig_debug']));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addGlobal('settings',$this->settings);
        $this->twig->addGlobal('userRole',$this->userRole);
        $this->twig->addGlobal('root',$this->settings['main_dir']);
        return $this->twig;
    }

    protected function systemIsInstalled(){
        return file_exists('../_install.php') ? false : true;
    }

    public function isAdmin(){
        return ($this->userRole == 'admin') ? true : false;
    }

    public function dieIfNotAdmin(){
        if(!$this->isAdmin()) die('You do not have permissions.');
    }

    public function getUserRole(){
        return $this->userRole;
    }

    public function getManager(){
        return $this->manager;
    }

    protected function loadConfig(){
        $json_data = file_get_contents('../data/config.json');
        $this->settings = json_decode($json_data, true);
        if($this->settings['dev_mode']){
            $this->settings['twig_cache'] = false;
            $this->settings['twig_debug'] = true;
        } else {
            $this->settings['twig_cache'] = 'cache';
            $this->settings['twig_debug'] = false;
        }
    }

    public function getConfig(){
        return $this->settings;
    }
    public function saveConfig($settings){
        copy('../data/config.json', '../data/tmp/config-tmp-'.date('YmdHis').'.json');
        $fp = fopen('../data/config.json', 'w');
        fwrite($fp, json_encode($settings));
        fclose($fp);
    }
}