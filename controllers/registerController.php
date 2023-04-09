<?php
    require_once("models/registerModel.php");

class RegisterController {

    public $model;

    public function __construct() {
        $this->model = new RegisterModel();
    }

    public function invoke() {
        $result = $this->model->register();

        if ($result) {
            header('Location: ./');
        }
    }

}

?>