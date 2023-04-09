<?php

class User {

    public $userID;
    public $username;
    public $firstName;
    public $lastName;
    public $email;
    public $isAdmin;

    public function __construct($userID, $firstName, $lastName, $email, $username, $isAdmin) {
        $this->userID = $userID;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->username = $username;
        $this->isAdmin = $isAdmin;
    }

}