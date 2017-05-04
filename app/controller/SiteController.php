<?php

class SiteController
{
    protected $lang;
    protected $twig;
    protected $request;
    protected $siteConfig;

    function __construct($ctrlParams)
    {
        $this->lang = $ctrlParams['lang'];
        $this->twig = $ctrlParams['twig'];
        $this->request = $ctrlParams['request'];
        $this->manager = new \Crayon\CrayonManager();
        $this->siteConfig = $ctrlParams['settings'];
        $this->twig->addGlobal('languages',$this->manager->getLanguagesList());
    }

    protected function getTemplate($tmpl){
        $template = $tmpl;
        if(array_key_exists('template',$this->siteConfig) && $this->siteConfig['template'] !== '')
            if(file_exists('views/_templates/'.$this->siteConfig['template'].'/'.$tmpl))
                $template = '_templates/'.$this->siteConfig['template'].'/'.$tmpl;
        return $template;
    }

    /**
     * Static page
     */
    public function page($sp,$dp) {
        $page_data = file_get_contents('data/pages/'.$sp[0].'.json');
        $page = json_decode($page_data, true);
        $menu_data = file_get_contents('data/menus.json');
        $menus = json_decode($menu_data, true);
        $menu = $menus[$page['menu']];
        $translations = $this->manager->getTranslations($menu['lang']);
        $this->twig->addGlobal('currentPath',$this->request['currentPath']);
        if($this->request['isAjax']){
            $res['content'] = $this->twig->render('pages/'.$sp[0].'.html.twig', array('template' => 'ajax.content.html.twig','t' => $translations));
            $res['scripts'] = $this->twig->render('pages/'.$sp[0].'.html.twig', array('template' => 'ajax.scripts.html.twig','t' => $translations));
            header('Content-Type: application/json');
            echo json_encode($res);
        } else {
            echo $this->twig->render('pages/'.$sp[0].'.html.twig', array(
                'template' => $this->getTemplate('base.html.twig'),
                'pageTitle' => $page['title'],
                'pageDescription' => $page['description'],
                'menu' => $menu,
                't' => $translations
            ));
        }
    }

    /**
     * Contact page
     */
    public function contact($sp,$dp) {

        $menu_data = file_get_contents('data/menus.json');
        $menus = json_decode($menu_data, true);
        $menu = $menus[$sp[0]];
        $translations = $this->manager->getTranslations($menu['lang']);
        if($this->request['isAjax']){
            $res['content'] = $this->twig->render('contact.html.twig', array('template' => 'ajax.content.html.twig','t' => $translations));
            $res['scripts'] = $this->twig->render('contact.html.twig', array('template' => 'ajax.scripts.html.twig','t' => $translations));
            header('Content-Type: application/json');
            echo json_encode($res);
        } else {
            echo $this->twig->render('contact.html.twig', array(
                'template' => $this->getTemplate('base.html.twig'),
                'menu' => $menu,
                't' => $translations
            ));
        }
    }

    /**
     * Contact send
     */
    public function contactSend($sp,$dp) {
        $res['status'] = 'error';
        $res['message'] = 'Your message was not sent. Please try later or send me a mail to <a href="mailto:'.$this->siteConfig['admin_email'].'">'.$this->siteConfig['admin_email'].'</a>';
        if(isset($_POST['email'])){
            $contactForm['email'] = $this->manager->testString($_POST['email']);
            $contactForm['message'] = $this->manager->testString($_POST['message']);
            $isSend = $this->manager->sendEmail(
                $this->siteConfig['admin_email'],
                $contactForm['email'],
                'Contact from '.$this->siteConfig['site_name'],
                $contactForm['message']
            );
            if($isSend === true){
                $res['status'] = 'success';
                $res['message'] = 'Thank you for your message.';
            } else {
                $res['status'] = 'error';
                $res['message'] = $isSend;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    /**
     * Get translation JS
     */
    public function jsTranslation($sp,$dp) {
        $lang = $this->manager->testString($_GET['lang']);
        $translation = $this->manager->getTranslations($lang);
        header("Content-type: text/javascript");
        echo "var translate = {";
        $translationLenght = count($translation);
        $i = 1;
        foreach ($translation as $key=>$translate){
            echo "\"".$key."\":\"".$translate."\"";
            if($i<$translationLenght)
                echo ",";
            $i++;
        }
        echo "};";
    }

    /**
     * 404
     */
    public function error404() {
        echo $this->twig->render($this->getTemplate('404.html.twig'), array(
            'template' => $this->getTemplate('base.html.twig')
        ));
    }
}