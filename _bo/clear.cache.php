<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("clear.cache");

$app->getManager()->clearCache('../cache');

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);