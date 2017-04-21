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
    }

    /**
     * Static page
     */
    public function page($sp,$dp) {
        $page_data = file_get_contents('data/pages/'.$sp[0].'.json');
        $page = json_decode($page_data, true);
        $menu_data = file_get_contents('data/menus.json');
        $menu = json_decode($menu_data, true);
        $this->twig->addGlobal('currentPath',$this->request['currentPath']);
        if($this->request['isAjax']){
            $res['content'] = $this->twig->render('pages/'.$sp[0].'.html.twig', array('template' => 'ajax.content.html.twig'));
            $res['scripts'] = $this->twig->render('pages/'.$sp[0].'.html.twig', array('template' => 'ajax.scripts.html.twig'));
            header('Content-Type: application/json');
            echo json_encode($res);
        } else {
            echo $this->twig->render('pages/'.$sp[0].'.html.twig', array(
                'template' => 'base.html.twig',
                'pageTitle' => $page['title'],
                'pageDescription' => $page['description'],
                'menu' => $menu[$page['menu']],
            ));
        }
    }

    /**
     * Contact page
     */
    public function contact($sp,$dp) {

        $menu_data = file_get_contents('data/menus.json');
        $menu = json_decode($menu_data, true);

        if($this->request['isAjax']){
            $res['content'] = $this->twig->render('contact.html.twig', array('template' => 'ajax.content.html.twig'));
            $res['scripts'] = $this->twig->render('contact.html.twig', array('template' => 'ajax.scripts.html.twig'));
            header('Content-Type: application/json');
            echo json_encode($res);
        } else {
            echo $this->twig->render('contact.html.twig', array(
                'template' => 'base.html.twig',
                'menu' => $menu[$sp[0]],
            ));
        }
    }

    /**
     * Contact page
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
     * 404
     */
    public function error404() {
        echo $this->twig->render('404.html.twig', array());
    }
}