<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();

$post = array();
if($_POST['post_id']) {
    $post['id'] = $app->getManager()->testString($_POST['post_id']);
    $post['title'] = $app->getManager()->testString($_POST['post_title']);
    $post['slug'] = $app->getManager()->testString($_POST['post_slug']);
    $post['title_color'] = $app->getManager()->testString($_POST['post_title_color']);
    $post['category_id'] = $app->getManager()->testString($_POST['post_category_id']);
    $post['intro'] = $app->getManager()->testString($_POST['post_intro']);
    $post['content'] = $app->getManager()->testString($_POST['post_content']);
    $post['intro_image'] = $app->getManager()->testString($_POST['post_intro_image']);
    $post['main_image'] = $app->getManager()->testString($_POST['post_main_image']);
    $post['publication_date'] = $app->getManager()->testString($_POST['post_publication_date']);
}
$app->getManager()->saveBlogPost($post);

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);
