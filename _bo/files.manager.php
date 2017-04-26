<?php
require_once '../vendor/autoload.php';

$app = new \CrayonBo\CrayonBo();
$mainDir = '../images/pages/';
$dir = $mainDir;
$upDir = null;
if(isset($_GET['dir'])){
    $dir .= $app->getManager()->testString($_GET['dir']);
    $tmp = explode('/',$dir);
    array_pop($tmp);
    array_pop($tmp);
    $upDir = implode('/',$tmp).'/';
    $upDir = str_replace($mainDir,'',$upDir);
}

$dirs = array_filter(glob("$dir*"), 'is_dir');
foreach ($dirs as $k=>$d){
    $dirs[$k] = str_replace($mainDir,'',$dirs[$k]);
}

$files = array_filter(glob("$dir*"), 'is_file');
foreach ($files as $k=>$f){
    $files[$k] = str_replace($mainDir,'',$files[$k]);
}



echo $app->twig->render('files.manager.html.twig', array(
    'menuPage' => 'files.m',
    'dirs' => $dirs,
    'files' => $files,
    'upDir' => $upDir
));
