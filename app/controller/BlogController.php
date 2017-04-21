<?php

class BlogController extends SiteController {

    protected $manager;

    function __construct($ctrlParams) {
        parent::__construct($ctrlParams);
        $this->manager = new \Crayon\CrayonManager();
    }

    public function articlesList($sp,$dp){
        $this->twig->addGlobal('currentPath',$this->request['currentPath']);
        $categoryId = (isset($sp[0]) && $sp[0] != 0) ? $sp[0] : null;
        $page = (isset($dp['page'])) ? $dp['page'] : '1';
        $category = $this->manager->getCategory($categoryId);
        $menuName = (isset($sp[1])) ? $sp[1] : $category['menu'];
        $menu = $this->manager->getMenu($menuName);
        $articles = $this->manager->getArticles($categoryId,$page,$menuName,null,true);


        if($this->request['isAjax']){
            $res['content'] = $this->twig->render('blog/articles.html.twig', array(
                'currentPageNumber' => $page,
                'template' => 'ajax.content.html.twig',
                'articles' => $articles['results'],
                'pagination' => $articles['paginator']
            ));
            $res['bodyClass'] = 'blog';
            header('Content-Type: application/json');
            echo json_encode($res);
        }
        else {
            echo $this->twig->render('blog/articles.html.twig', array(
                'currentPageNumber' => $page,
                'template' => 'base.html.twig',
                'articles' => $articles['results'],
                'pageTitle' => 'Blog',
                'pageDescription' => 'Desc',
                'menu' => $menu,
                'pagination' => $articles['paginator']
            ));
        }

    }

    public function article($sp,$dp){
        $article = $this->manager->getArticle($dp['article']);
        try {
            $category = $this->manager->getCategory($article['category_id']);
            $menu = $this->manager->getMenu($category['menu']);
        } catch(\ErrorException $e){
            header($_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
            $this->error404();
            die();
        }

        if($this->request['isAjax']){
            $res['contentTitle'] = $this->twig->render('blog/article.html.twig', array(
                'template' => 'ajax.contentTitle.html.twig',
                'article' => $article
            ));
            $res['content'] = $this->twig->render('blog/article.html.twig', array(
                'template' => 'ajax.content.html.twig',
                'article' => $article,
                'category' => $category
            ));
            $res['bodyClass'] = 'blog';
            header('Content-Type: application/json');
            echo json_encode($res);
        }
        else {
            echo $this->twig->render('blog/article.html.twig', array(
                'template' => 'base.html.twig',
                'article' => $article,
                'pageTitle' => 'Blog',
                'pageDescription' => 'Desc',
                'menu' => $menu,
                'category' => $category
            ));
        }
    }

}