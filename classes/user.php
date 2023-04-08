<?php

class User {

    public $user_id;
    public $username;
    public $firstName;
    public $lastName;
    public $email;
    public $isAdmin;

    public function __construct($user_id, $firstName, $lastName, $email, $username, $isAdmin) {
        $this->user_id = $user_id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->username = $username;
        $this->isAdmin = $isAdmin;
    }

}