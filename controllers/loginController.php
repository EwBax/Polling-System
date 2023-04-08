<?php

require_once("models/loginModel.php");

class LoginController {

    public $model;

    public function __construct() {
        $this->model = new LoginModel();
    }

    public function invoke() {
        $result = $this->model->getlogin();

        if ($result) {
            header("Location: user/userPanel.php");

        } else {;
            include 'views/login.php';

        }
    }

}

?>