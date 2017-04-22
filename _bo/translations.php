<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$translations = $app->getManager()->getTranslations();


echo $app->twig->render('translations.html.twig', array(
    'translations' => $translations,
    'menuPage' => 'internationalization'
));
