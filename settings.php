<?php
$dev_mode = true;

$twigCache = 'cache';
$twigDebug = false;
if($dev_mode){
    $twigCache = false;
    $twigDebug = true;
}
