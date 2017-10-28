<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$manager = new \Crayon\CrayonManager();
$app->dieIfUserNotAllowed("blog.posts");
$page = (isset($_GET['page'])) ? $app->getManager()->testString($_GET['page']) : 1;
$articlesPerPage = 10;

$posts = $manager->getArticles(null,$page,null,$articlesPerPage);
$categories = $app->getManager()->getBlogCategories();


echo $app->twig->render('blog.posts.html.twig', array(
    'posts' => $posts,
    'categories' => $categories,
    'menuPage' => 'blog.p',
    'currentPage' => $page,
    'articlesPerPage' => $articlesPerPage
));
