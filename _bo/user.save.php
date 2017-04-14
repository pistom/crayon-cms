<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$users = $app->getManager()->getUsersList();
$oldUserName = (isset($_GET['user'])) ? $app->getManager()->testString($_GET['user']) : null;
$user = array();
$userName = '';
if($_POST){
    $userName = $app->getManager()->testString($_POST['username']);
    $user[$userName]['role'] =  $app->getManager()->testString($_POST['userrole']);;
    $user[$userName]['pwd'] = md5($app->getManager()->testString($_POST['password']));
};

if($oldUserName)
    unset($users[$oldUserName]);

$users[$userName] = $user[$userName];
$app->getManager()->saveUsersList($users);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);