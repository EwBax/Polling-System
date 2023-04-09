<?php

require_once("controllers/registerController.php");
$controller = new RegisterController();

// Code from here https://www.w3schools.com/php/php_form_url_email.asp

$firstNameErr = $lastNameErr = $emailErr = $usernameErr = $passwordErr = $confirmPasswordErr = "";
$firstName = $lastName = $email = $username = $password = $confirmPassword = "";
$valid = true;

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validating first and last name
    $firstName = test_input($_POST["first_name"]);
    if (!preg_match("/^[a-zA-Z]{1,20}$/",$firstName)) {
        $firstNameErr = "Only letters allowed. Name must be between 1 and 20 characters long.";
        $valid = false;
    }

    $lastName = test_input($_POST["last_name"]);
    if (!preg_match("/^[a-zA-Z]{1,20}$/",$lastName)) {
        $lastNameErr = "Only letters allowed. Name must be between 1 and 20 characters long.";
        $valid = false;
    }
  

    // Validating email
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $valid = false;
    }

    // Checking if email already exists in db
    if ($controller->model->checkEmail($email)) {
        $emailErr = "This email is already assosciated with an account.";
        $valid = false;
    }
  
    $username = test_input($_POST['username']);
    if (!preg_match("/^[a-zA-Z\d]{4,20}$/", $username)) {
        $usernameErr = "Invalid username. Must be between 4-20 characters long, and only contain letters and numbers.";
        $valid = false;
    }

    if ($controller->model->checkUsername($username)) {
        $usernameErr = "That username has already been taken.";
        $valid = false;
    }
  
    $password = test_input($_POST['password']);
    if (!preg_match("/^.{8,20}$/", $password)) {
        $passwordErr = "Invalid password. Must be between 8-20 characters long.";
        $valid = false;
    }

    $confirmPassword = test_input($_POST['confirm_password']);
    if ($password != $confirmPassword) {
        $confirmPasswordErr = "Passwords must match.";
        $valid = false;
    }

    if ($valid) {
        $controller->invoke();
    }

  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ewan's Polling System - Registration</title>
</head>
<body>

    <h1>Please register below to vote</h1>

    <form action="" method="POST">
        <label for="first_name">First Name: </label>
        <input type="text" name="first_name" id="first_name" value="<?php echo $firstName; ?>" placeholder="first name" required="required"> <?php echo $firstNameErr; ?>
        <br>
        <br>
        <label for="last_name">Last Name: </label>
        <input type="text" name="last_name" id="last_name" value="<?php echo $lastName; ?>" placeholder="last name" required="required"> <?php echo $lastNameErr; ?>
        <br>
        <br>
        <label for="email">Email Address: </label>
        <input type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="email" required="required"> <?php echo $emailErr; ?>
        <br>
        <br>
        <label for="username">Username: </label>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>" placeholder="username" required="required"> <?php echo $usernameErr; ?>
        <br>
        <br>
        <label for="passsword">Password: </label>
        <input type="password" name="password" id="password" value="" placeholder="password" required="required"> <?php echo $passwordErr; ?>
        <br>
        <br>
        <label for="confirm_password">Confirm Password: </label>
        <input type="password" name="confirm_password" id="confirm_password" value="" placeholder="confirm password" required="required"> <?php echo $confirmPasswordErr; ?>
        <br>
        <br>
        <button type="submit" name="register" id="register">Register</button>
        <button onclick="window.location.href = './'">Back to Login</button>
    </form>

</body>
</html>