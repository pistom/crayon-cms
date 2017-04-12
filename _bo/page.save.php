<?php
include_once 'app.php';

$oldPageName = (isset($_GET['page'])) ? $manager->testString($_GET['page']) : null;
if($_POST) {
    $page = ($oldPageName) ? $manager->getPage($oldPageName) : array();
    $page['oldPageName'] = $oldPageName;
    $page['pageName'] = $manager->testString($_POST['pageName']);
    $page['title'] = $manager->testString($_POST['pageTitle']);
    $page['description'] = $manager->testString($_POST['pageDescription']);
    $page['menu'] = $manager->testString($_POST['pageMenu']);
    $page['content'] = $_POST['pageContent'];
}
$manager->savePage($page);

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);