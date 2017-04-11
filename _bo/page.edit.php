<?php
include_once 'app.php';

$page = array();
$pageName = '';
$menus = $manager->getMenusList();
if(isset($_GET['page'])) {
    $pageName = $manager->testString($_GET['page']);
    $page = $manager->getPage($pageName);
}

echo $twig->render('page.edit.html.twig', array(
    'page' => $page,
    'pageName' => $pageName,
    'userRole' => $userRole,
    'menus' => $menus
));
