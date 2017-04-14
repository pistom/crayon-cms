<?php
require_once '../vendor/autoload.php';

$app = new \CrayonBo\CrayonBo();
echo $app->twig->render('home.html.twig', array(
    'menuPage' => 'home'
));
