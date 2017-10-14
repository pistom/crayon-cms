<?php
namespace Crayon;

class Crayon {

    protected $router;
    protected $lang;
    protected $twig;
    protected $routes;
    protected $settings;

    function __construct($router) {
        if($this->systemIsInstalled()) {
            $this->getConfig();
            $this->loadTwig();
            $this->loadControllers();
            $this->lang = 'en';
            $this->router = $router;
            $this->routes = $this->loadRoutes();
        } else {
            header('location:_install.php');
        }
    }

    public static function systemIsInstalled(){
        $installFileExists = file_exists('_install.php') ? true : false;
        $vendorExists = is_dir('vendor') ? true : false;
        $altorouterInstalled = is_dir('vendor/altorouter') ? true : false;
        $phpmailerInstalled = is_dir('vendor/phpmailer') ? true : false;
        $twigInstalled = is_dir('vendor/twig') ? true : false;
        if( !$vendorExists ||
            !$altorouterInstalled ||
            !$phpmailerInstalled ||
            !$twigInstalled ){
            echo '<pre><span style="color:crimson">No dependencies found</span><br>';
            echo '<span style="color:slategray">Use composer or download a package containing dependencies</span></pre>';
            die();
        } elseif ($installFileExists) {
            header('location:_install.php');
            die();
        }
        return true;
    }

    public function run() {
        $this->setTwigGlobals();
        $ctrlParams = array(
            'lang'=>$this->lang,
            'twig'=>$this->twig,
            'request'=>$this->getRequestParams(),
            'settings'=>$this->settings
        );
        $this->router->addMatchTypes(array('slug' => '[a-zA-Z0-9-_]++'));
        $match = $this->router->match();
        if ($match === false) {
            header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
            $controller = new \SiteController($ctrlParams);
            $controller->error404();
        } else {
            $this->twig->addGlobal('currentMatch',$match['target']);
            $route = explode("#", $match['target']);
            $controllerName = $route[0];
            $functionName = $route[1];
            $staticParams = array();
            if(isset($route[2]))
                $staticParams = explode("|",$route[2]);
            $dynamicParams = $match['params'];
            $controller = new $controllerName($ctrlParams);
            try{
                $controller->{$functionName}($staticParams,$dynamicParams);
            } catch (Error $e) {
                echo $e->getMessage();
            }
        }
    }

    protected function loadControllers(){
        require_once ('app/controller/SiteController.php');
        $controllers = glob('app/controller/*.php');
        foreach ($controllers as $c) {
            if($c !== 'app/controller/SiteController.php')
                require($c);
        }
    }

    protected function loadRoutes(){
        $json_data = file_get_contents('data/routing.json');
        $routes = json_decode($json_data, true);
        foreach ($routes as $route=>$option){
            $this->router->map(
                $option['type'],
                $this->settings['main_dir'].$option['path'],
                $option['controller'].'#'.$option['function'].'#'.$option['variables'],
                $route,
                $option['name']
            );
            $routes[$route]['path'] = preg_replace('/\[.*:.*\]\?*\//','',$option['path']);
        }
        $this->loadAdditionalRoutes();
        return $routes;
    }

    protected function getRequestParams(){
        $params = array();
        $bodyIsLoaded = (isset($_GET['ajax']) && $_GET['ajax']==='true') ? true : false;
        $params['isAjax'] = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') && $bodyIsLoaded ?
            true : false;
        $params['currentPath'] = $_SERVER["REQUEST_URI"];
        return $params;
    }

    protected function getConfig(){
        $json_data = file_get_contents('data/config.json');
        $this->settings = json_decode($json_data, true);
        if($this->settings['dev_mode']){
            $this->settings['twig_cache'] = false;
            $this->settings['twig_debug'] = true;
        } else {
            $this->settings['twig_cache'] = 'cache';
            $this->settings['twig_debug'] = false;
        }
    }

    protected function loadTwig(){
        $loader = new \Twig_Loader_Filesystem('views');
        $this->twig = new \Twig_Environment($loader, array('cache' => $this->settings['twig_cache'],'debug' => $this->settings['twig_debug']));
        $this->twig->addExtension(new \Twig_Extension_Debug());
    }

    private function loadAdditionalRoutes(){
        $this->router->map('POST',$this->settings['main_dir'].'/contact/send','SiteController#contactSend','contact_send','Sending contact form');
        $this->router->map('GET',$this->settings['main_dir'].'/js/translation.js','SiteController#jsTranslation','js_translation','Getting translation for JS');
    }

    private function getAssets()
    {
        try {
            return file_exists('./dist/manifest.json') ?
                json_decode(file_get_contents('./dist/manifest.json'), true) : null;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function setTwigGlobals()
    {
        $this->twig->addGlobal('assets', $this->getAssets());
        $this->twig->addGlobal('routes', $this->routes);
        $this->twig->addGlobal('settings', $this->settings);
        $this->twig->addGlobal('root', $this->settings['main_dir']);
    }
}
