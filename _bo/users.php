<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("users");

$users = $app->getManager()->getUsersList();

echo $app->twig->render('users.html.twig', array(
    'users' => $users,
    'menuPage' => 'users'
));
