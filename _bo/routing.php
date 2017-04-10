<?php
include_once 'app.php';
$routes = $manager->getRoutesList();
$controllers = $manager->getControllersList();


echo $twig->render('routing.html.twig', array(
    'routes' => $routes,
    'userRole' => $userRole,
    'controllers' => $controllers
));
