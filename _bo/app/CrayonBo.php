<?php
namespace CrayonBo;

class CrayonBo {

    protected $userRole;
    protected $settings;
    protected $manager;
    public $twig;

    function __construct() {
        session_start();
        include_once 'verification.php';
        include_once '../settings.php';
        require_once 'manager/boManager.php';
        $this->userRole = $userRole;
        $this->settings['twigDebug'] = false;
        $this->settings['twigCache'] = 'cache';
        $this->settings['devMode'] = $dev_mode;
        if($this->settings['devMode']){
            $this->settings['twigCache'] = false;
            $this->settings['twigDebug'] = true;
        }
        $this->settings['tinyMCE_API_key'] = $tinyMCE_API_key;
        $this->loadTwig();
        $this->manager = new \boManager();
    }

    protected function loadTwig(){
        $loader = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($loader, array('cache' => $this->settings['twigCache'],'debug' => $this->settings['twigDebug']));
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addGlobal('settings',$this->settings);
        $this->twig->addGlobal('userRole',$this->userRole);
        return $this->twig;
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
}