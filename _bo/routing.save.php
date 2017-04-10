<?php
include_once 'app.php';
$routes = $manager->getRoutesList();
$controllers = $manager->getControllersList();

$routes = array();
if($_POST){
    $i = 0;
    $r = '';
    foreach ($_POST as $key=>$route){
        $tmp = explode('-',$key);
        $k = $manager->testString($tmp[1]);
        if($i%9 == 0){
            $r = $manager->testString($route);
            $routes[$r] = array();
        }
        else{
            $routes[$r][$k] = $manager->testString($route);
        }
        $i++;
    }
};


$manager->saveRoutesList($routes);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);