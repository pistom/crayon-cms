<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("gallery.item.delete");

$manager = new \Crayon\CrayonManager();

$itemId = $app->getManager()->testString($_POST['itemId']);
$app->getManager()->deleteGalleryItem($itemId);



header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);