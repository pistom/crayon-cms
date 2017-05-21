<?php
require_once '../vendor/autoload.php';

$app = new \CrayonBo\CrayonBo();
$settings = $app->getConfig();
$users = $app->getManager()->getUsersList();
$changePwd = ($users["admin"]["pwd"] === crypt("admin",$settings["salt"])) ? true : false;
echo $app->twig->render('home.html.twig', array(
    'menuPage' => 'home',
    'changePwd' => $changePwd
));
