<?php

class GalleryController extends SiteController
{
    protected $manager;

    function __construct($ctrlParams)
    {
        parent::__construct($ctrlParams);
    }

    public function category($sp, $dp)
    {
        $this->twig->addGlobal('currentPath', $this->request['currentPath']);
        $categoryId = (isset($sp[0]) && $sp[0] != 0) ? $sp[0] : null;
        $page = (isset($dp['page'])) ? $dp['page'] : '1';
        $category = $this->manager->getGalleryCategory($categoryId);
        $menuName = (isset($sp[1])) ? $sp[1] : $category['menu'];
        $menu = $this->manager->getMenu($menuName);
        $translations = $this->manager->getTranslations($menu['lang']);
        $galleryCategoryItems = $this->manager->getGalleryCategoryItems($categoryId, $page, $menuName, null, true);


        if ($this->request['isAjax']) {
            $res['content'] = $this->twig->render($this->getTemplate('gallery/category.html.twig'), array(
                'currentPageNumber' => $page,
                'template' => 'ajax.content.html.twig',
                'items' => $galleryCategoryItems['results'],
                'pagination' => $galleryCategoryItems['paginator'],
                't' => $translations
            ));
            $res['bodyClass'] = 'blog';
            header('Content-Type: application/json');
            echo json_encode($res);
        } else {
            echo $this->twig->render($this->getTemplate('gallery/category.html.twig'), array(
                'currentPageNumber' => $page,
                'template' => $this->getTemplate('base.html.twig'),
                'items' => $galleryCategoryItems['results'],
                'pageTitle' => 'Blog',
                'pageDescription' => 'Desc',
                'menu' => $menu,
                'pagination' => $galleryCategoryItems['paginator'],
                't' => $translations
            ));
        }

    }
}