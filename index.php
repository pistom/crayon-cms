<?php
require 'vendor/autoload.php';
include_once 'settings.php';
$twigDebug = false;
if($dev_mode){
    $twigCache = false;
    $twigDebug = true;
}

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array('cache' => $twigCache,'debug' => $twigDebug));
$twig->addExtension(new Twig_Extension_Debug());
$routing = new AltoRouter();

$app = new \Crayon\Crayon($routing,$twig);
$app->run();