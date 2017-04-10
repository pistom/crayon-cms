<?php
include_once 'app.php';
$menus = $manager->getMenusList();


echo $twig->render('menus.html.twig', array(
    'menus' => $menus
));
