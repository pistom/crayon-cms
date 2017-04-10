<?php session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') :
//    $logins = array('admin' => 'admin','test' => 'test');
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
    include_once '../settings.php';
    require_once '../vendor/autoload.php';
    $loader = new Twig_Loader_Filesystem('views');
    $twig = new Twig_Environment($loader, array('cache' => $twigCache,'debug' => $twigDebug));
    echo $twig->render('login.html.twig', array());
endif;








