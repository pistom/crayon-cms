<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$categories = $app->getManager()->getGalleryCategories();

echo $app->twig->render('gallery.categories.html.twig', array(
    'menuPage' => 'gallery.c',
    'categories' => $categories
));
