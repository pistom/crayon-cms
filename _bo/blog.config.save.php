<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$blogConfig = $app->getManager()->getBlogConfig();

if($_POST){

    $blogConfig['articles_per_page'] = $app->getManager()->testString($_POST['articles_per_page']);

};


$app->getManager()->saveBlogConfig($blogConfig);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);