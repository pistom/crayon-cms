<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$category = null;
$categoryId = '';
$menus = $app->getManager()->getMenusList();
if(isset($_GET['category'])) {
    $category = $app->getManager()->getBlogCategory($app->getManager()->testString($_GET['category']));
} else {
    $category['id'] = end($app->getManager()->getBlogCategories())['id']+1;
}

echo $app->twig->render('blog.category.edit.html.twig', array(
    'category' => $category,
    'menus' => $menus
));
