<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$users = $app->getManager()->getUsersList();
$userName = $app->getManager()->testString($_POST['user']);

unset($users[$userName]);

$app->getManager()->saveUsersList($users);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);