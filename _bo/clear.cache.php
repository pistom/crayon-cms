<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$app->getManager()->clearCache('../cache');

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);