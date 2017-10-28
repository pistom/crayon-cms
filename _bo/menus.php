<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("menus");

$menus = $app->getManager()->getMenusList();


echo $app->twig->render('menus.html.twig', array(
    'menus' => $menus,
    'menuPage' => 'menus'
));
