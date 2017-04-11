<?php
include_once 'app.php';
$routes = $manager->getRoutesList();
$menu = null;
$menuName = '';
if(isset($_GET['menu'])) {
    $menus = $manager->getMenusList();
    $menuName = $manager->testString($_GET['menu']);
    $menu = $menus[$menuName];
}

echo $twig->render('menu.edit.html.twig', array(
    'menu' => $menu,
    'menuName' => $menuName,
    'routes' => $routes,
    'userRole' => $userRole
));
