<?php
require_once '../vendor/autoload.php';

$app = new \CrayonBo\CrayonBo();
$mainDir = '../images/pages/';
$dir = $mainDir;
$upDir = null;
if(isset($_GET['dir']) && $_GET['dir'] !== ''){
    $dir .= str_replace('../','',$app->getManager()->testString($_GET['dir']));
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
$filesList = array();
foreach ($files as $k=>$f){
    $filesList[$k] = array('name'=>str_replace($mainDir,'',$files[$k]));
    $filesList[$k]['type'] = mime_content_type($f);
    if($is = getimagesize($f)){
        $filesList[$k]['dimensions'] = array($is[0],$is[1]);
    }
}

$params = array(
    'template' => 'base.html.twig',
    'menuPage' => 'files.m',
    'dirs' => $dirs,
    'files' => $filesList,
    'upDir' => $upDir
);

if(isset($_GET['inner']) && $_GET['inner'] !== '') {
    $params['template'] = 'base.content.html.twig';
    $params['inner'] = true;
}
echo $app->twig->render('files.manager.html.twig', $params);



