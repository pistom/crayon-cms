<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$routes = $app->getManager()->getRoutesList();
$controllers = $app->getManager()->getControllersList();

$routes = array();
if($_POST){
    $i = 0;
    $r = '';
    foreach ($_POST as $key=>$route){
        $tmp = explode('-',$key);
        $k = $app->getManager()->testString($tmp[1]);
        if($i%9 == 0){
            $r = $app->getManager()->testString($route);
            $routes[$r] = array();
        }
        else{
            $routes[$r][$k] = $app->getManager()->testString($route);
        }
        $i++;
    }
};


$app->getManager()->saveRoutesList($routes);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);