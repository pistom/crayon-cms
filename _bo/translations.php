<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("translations");

$translations = $app->getManager()->getTranslations();
$languages = $app->getManager()->getLanguagesList();


echo $app->twig->render('translations.html.twig', array(
    'translations' => $translations,
    'menuPage' => 'inter.t',
    'languages' => $languages
));
