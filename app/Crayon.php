<?php
namespace Crayon;

class Crayon {

    protected $router;
    protected $lang;
    protected $twig;
    protected $routes;

    function __construct($router, $twig) {
        $this->lang = 'en';
        $this->router = $router;
        $this->twig = $twig;
        $this->loadControllers();
        $this->routes = $this->loadRoutes();
    }

    public function run() {
        $this->twig->addGlobal('routes',$this->routes);
        $ctrlParams = array(
            'lang'=>$this->lang,
            'twig'=>$this->twig,
            'request'=>$this->getRequestParams()
        );
        $match = $this->router->match();
        if ($match === false) {
            header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
            $controller = new \SiteController($ctrlParams);
            $controller->error404();
        } else {
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
                $option['path'],
                $option['controller'].'#'.$option['function'].'#'.$option['variables'],
                $route,
                $option['name']
            );
        }
        return $routes;
    }

    protected function getRequestParams(){
        $params = array();
        $params['isAjax'] = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ?
            true : false;
        $params['currentPath'] = $_SERVER["REQUEST_URI"];
        return $params;
    }
}