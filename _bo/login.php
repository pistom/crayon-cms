<?php session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') :
    $json_data = file_get_contents('../data/users.json');
    $logins = json_decode($json_data, true);

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? md5($_POST['password']) : '';

    $res = array();
    if (isset($logins[$username]) && $logins[$username]['pwd'] === $password){
        $_SESSION['UserData']['username']=$_POST['username'];
        $_SESSION['UserData']['role']=$logins[$username]['role'];
        $res['status'] = 'success';
    } else {
        $res['status'] = 'error';
    }
    $res['info'] = $username." ".$password;
    header('Content-Type: application/json');
    echo json_encode($res);
else :

    // SETTINGS
    $json_data = file_get_contents('../data/config.json');
    $settings = json_decode($json_data, true);
    if($settings['dev_mode']){
        $settings['twig_cache'] = false;
        $settings['twig_debug'] = true;
    } else {
        $settings['twig_cache'] = 'cache';
        $settings['twig_debug'] = false;
    }

    require_once '../vendor/autoload.php';
    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader, array('cache' => $settings['twig_cache'],'debug' => $settings['twig_debug']));
    echo $twig->render('login.html.twig', array(
        'siteName' => $settings['site_name'],
        'siteColor' => $settings['site_color']
    ));
endif;








