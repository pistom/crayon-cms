<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$post = array();
$postId = '';
$posts = $app->getManager()->getAllBlogPosts();
$categories = $app->getManager()->getBlogCategories();
if(isset($_GET['postId'])) {
    $post = $app->getManager()->getBlogPost($app->getManager()->testString($_GET['postId']));
} else {
    $post['id'] = end($posts)['id']+1;
}

echo $app->twig->render('blog.post.edit.html.twig', array(
    'post' => $post,
    'categories' => $categories,
    'menuPage' => 'blog',
    'now' => new \DateTime
));
