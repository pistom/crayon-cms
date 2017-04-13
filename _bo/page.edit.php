<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();

$page = array();
$pageName = '';
$menus = $app->getManager()->getMenusList();
if(isset($_GET['page'])) {
    $pageName = $app->getManager()->testString($_GET['page']);
    $page = $app->getManager()->getPage($pageName);
}

echo $app->twig->render('page.edit.html.twig', array(
    'page' => $page,
    'pageName' => $pageName,
    'menus' => $menus
));
