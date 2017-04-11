<?php
include_once 'app.php';
$menus = $manager->getMenusList();
$menuName = $manager->testString($_POST['menu']);

unset($menus[$menuName]);

$manager->saveMenusList($menus);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);