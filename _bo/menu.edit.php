<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$routes = $app->getManager()->getRoutesList();
$menu = null;
$menuName = '';
if(isset($_GET['menu'])) {
    $menus = $app->getManager()->getMenusList();
    $menuName = $app->getManager()->testString($_GET['menu']);
    $menu = $menus[$menuName];
}

echo $app->twig->render('menu.edit.html.twig', array(
    'menu' => $menu,
    'menuName' => $menuName,
    'routes' => $routes
));
