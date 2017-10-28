<?php

namespace CrayonBo;

class Verification
{
    function __construct($mainDir)
    {
        $this->mainDir = $mainDir;
    }

    public function verify()
    {
        if (!isset($_SESSION['UserData']['username'])) {
            header("location:" . $this->mainDir . "/_bo/login.php");
            exit;
        } else {
            return array(
                'userRole' => $_SESSION['UserData']['role'],
                'userName' => $_SESSION['UserData']['username']
            );
        }
    }
}
