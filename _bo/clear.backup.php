<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("clear.backup");

$app->getManager()->clearDirectory('../data/tmp');
$app->getManager()->clearDirectory('../data/tmp/pages');
$app->getManager()->clearDirectory('../data/tmp/blog');
$app->getManager()->clearDirectory('../views/tmp/blog/articles');
$app->getManager()->clearDirectory('../views/tmp');
$app->getManager()->clearDirectory('../views/tmp/pages');


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);