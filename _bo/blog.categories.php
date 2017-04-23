<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$categories = $app->getManager()->getBlogCategories();

echo $app->twig->render('blog.categories.html.twig', array(
    'menuPage' => 'blog.c',
    'categories' => $categories
));
