<?php
include_once 'app.php';
$pages = $manager->getPagesList();


echo $twig->render('pages.html.twig', array(
    'pages' => $pages,
    'userRole' => $userRole
));
