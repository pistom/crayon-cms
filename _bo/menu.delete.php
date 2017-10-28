<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("menu.delete");

$menus = $app->getManager()->getMenusList();
$menuName = $app->getManager()->testString($_POST['menu']);

unset($menus[$menuName]);

$app->getManager()->saveMenusList($menus);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);