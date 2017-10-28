<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$galleryConfig = $app->getManager()->getGalleryConfig();

if($_POST){

    $galleryConfig['items_per_page'] = $app->getManager()->testString($_POST['items_per_page']);

};


$app->getManager()->saveGalleryConfig($galleryConfig);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);