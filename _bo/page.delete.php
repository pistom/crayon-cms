<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$pageName = $app->getManager()->testString($_POST['page']);
$app->getManager()->deletePage($pageName);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);