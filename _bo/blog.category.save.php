<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();

$category = array();
if($_POST) {
    $category['id'] = $app->getManager()->testString($_POST['category_id']);
    $category['name'] = $app->getManager()->testString($_POST['category_name']);
    $category['menu'] = $app->getManager()->testString($_POST['category_menu']);
}
$app->getManager()->saveBlogCategory($category);

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);
