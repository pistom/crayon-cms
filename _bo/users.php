<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$users = $app->getManager()->getUsersList();

echo $app->twig->render('users.html.twig', array(
    'users' => $users
));
