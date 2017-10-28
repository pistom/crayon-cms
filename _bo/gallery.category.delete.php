<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("gallery.category.delete");

$app->getManager()->deleteGalleryCategory($app->getManager()->testString($_POST['category']));

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);