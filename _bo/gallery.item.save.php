<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();

$item = array();
if(key_exists('item_id', $_POST)) {
    $item['id'] = $app->getManager()->testString($_POST['item_id']);
    $item['title'] = $app->getManager()->testString($_POST['item_title']);
    $item['description'] = $app->getManager()->testString($_POST['item_description']);
    $item['slug'] = $app->getManager()->testString($_POST['item_slug']);
    $item['category_id'] = $app->getManager()->testString($_POST['item_category_id']);
    $item['image'] = $app->getManager()->testString($_POST['item_image']);
    $item['order'] = $app->getManager()->testString($_POST['item_order']);
}

$message = $app->getManager()->saveGalleryItem($item);

header('Content-Type: application/json');
$res['status'] = 'success';
$res['message'] = $message;
echo json_encode($res);
