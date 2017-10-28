<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("gallery.item.edit");

$item = array();
$itemId = '';
$items = $app->getManager()->getAllGalleryItems();
$categories = $app->getManager()->getGalleryCategories();
if(isset($_GET['itemId'])) {
    $item = $app->getManager()->getGalleryItem($app->getManager()->testString($_GET['itemId']));
} else {
    $item['id'] = end($item)['id']+1;
}

echo $app->twig->render('gallery.item.edit.html.twig', array(
    'item' => $item,
    'categories' => $categories,
    'menuPage' => 'gallery.i'
));
