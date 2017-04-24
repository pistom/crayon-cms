<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$languages = $app->getManager()->getLanguagesList();
$routes = $app->getManager()->getRoutesList();

echo $app->twig->render('languages.html.twig', array(
    'languages' => $languages,
    'menuPage' => 'inter.l',
    'routes' => $routes,
));
