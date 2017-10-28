<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("gallery.category.edit");

$category = null;
$categoryId = '';
$menus = $app->getManager()->getMenusList();
if(isset($_GET['category'])) {
    $category = $app->getManager()->getGalleryCategory($app->getManager()->testString($_GET['category']));
} else {
    $categories = $app->getManager()->getGalleryCategories();
    $category['id'] = end($categories)['id']+1;
}

echo $app->twig->render('gallery.category.edit.html.twig', array(
    'category' => $category,
    'menus' => $menus,
    'menuPage' => 'gallery.c',
));
