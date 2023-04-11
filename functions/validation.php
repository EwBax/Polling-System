<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateName($name) {
    return preg_match("/^[a-zA-Z]{1,20}$/", $name);
}

function validateUsername($username) {
    return preg_match("/^[a-zA-Z\d]{4,20}$/", $username);
}

?>