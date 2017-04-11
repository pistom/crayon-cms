<?php
session_start();
include_once 'verification.php';
include_once '../settings.php';
$twigDebug = false;
if($dev_mode){
    $twigCache = false;
    $twigDebug = true;
}
require_once '../vendor/autoload.php';
require_once 'manager/boManager.php';
$manager = new boManager();
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array('cache' => $twigCache,'debug' => $twigDebug));
$twig->addExtension(new Twig_Extension_Debug());