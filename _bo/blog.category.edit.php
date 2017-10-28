<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("blog.category.edit");

$category = null;
$categoryId = '';
$menus = $app->getManager()->getMenusList();
if(isset($_GET['category'])) {
    $category = $app->getManager()->getBlogCategory($app->getManager()->testString($_GET['category']));
} else {
    $categories = $app->getManager()->getBlogCategories();
    $category['id'] = end($categories)['id']+1;
}

echo $app->twig->render('blog.category.edit.html.twig', array(
    'category' => $category,
    'menus' => $menus,
    'menuPage' => 'blog.c',
));
