<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$routes = $app->getManager()->getRoutesList();
$controllers = $app->getManager()->getControllersList();


echo $app->twig->render('routing.html.twig', array(
    'routes' => $routes,
    'controllers' => $controllers
));
