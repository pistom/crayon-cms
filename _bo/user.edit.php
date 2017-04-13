<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$user = null;
$userName = '';
if(isset($_GET['user'])) {
    $users = $app->getManager()->getUsersList();
    $userName = $app->getManager()->testString($_GET['user']);
    $user = $users[$userName];
}

echo $app->twig->render('user.edit.html.twig', array(
    'user' => $user,
    'userName' => $userName
));
