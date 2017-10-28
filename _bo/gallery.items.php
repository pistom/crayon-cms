<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("gallery.items");
$manager = new \Crayon\CrayonManager();

$page = (isset($_GET['page'])) ? $app->getManager()->testString($_GET['page']) : 1;
$articlesPerPage = 10;

$items = $manager->getGalleryCategoryItems(null,$page,null,$articlesPerPage);
$categories = $app->getManager()->getGalleryCategories();


echo $app->twig->render('gallery.items.html.twig', array(
    'items' => $items,
    'categories' => $categories,
    'menuPage' => 'gallery.i',
    'currentPage' => $page,
    'articlesPerPage' => $articlesPerPage
));
