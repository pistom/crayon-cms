<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$blogConfig = $app->getManager()->getBlogConfig();

if($_POST){

    $blogConfig['articles_per_page'] = $app->getManager()->testString($_POST['articles_per_page']);
    $blogConfig['author_name'] = $app->getManager()->testString($_POST['author_name']);
    $blogConfig['author_desc'] = $app->getManager()->testString($_POST['author_desc']);
    $blogConfig['author_photo'] = $app->getManager()->testString($_POST['author_photo']);

};


$app->getManager()->saveBlogConfig($blogConfig);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);