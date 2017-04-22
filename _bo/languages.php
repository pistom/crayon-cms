<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$languages = $app->getManager()->getLanguagesList();


echo $app->twig->render('languages.html.twig', array(
    'languages' => $languages,
    'menuPage' => 'internationalization'
));
