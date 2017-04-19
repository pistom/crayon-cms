<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$manager = new \Crayon\CrayonManager();

$postId = $app->getManager()->testString($_POST['postId']);
$app->getManager()->deleteBlogPost($postId);



header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);