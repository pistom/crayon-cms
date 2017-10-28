<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("blog.config");
$config = $app->getManager()->getBlogConfig();

echo $app->twig->render('blog.config.html.twig', array(
    'menuPage' => 'blog.s',
    'config' => $config
));
