<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("blog.category.delete");

$app->getManager()->deleteBlogCategory($app->getManager()->testString($_POST['category']));

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);