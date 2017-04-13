<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();

$pages = $app->getManager()->getPagesList();


echo $app->twig->render('pages.html.twig', array(
    'pages' => $pages
));
