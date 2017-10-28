<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("languages");

$languages = $app->getManager()->getLanguagesList();
$routes = $app->getManager()->getRoutesList();

if($app::isAjaxRequest()){
    header('Content-Type: application/json');
    echo json_encode($languages);
} else {
    echo $app->twig->render('languages.html.twig', array(
        'languages' => $languages,
        'menuPage' => 'inter.l',
        'routes' => $routes,
    ));
}
