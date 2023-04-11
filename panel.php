<?php
require_once("controllers/panelController.php");
require_once("functions/signOut.php");
session_start();

// Checking if logged in and if it has been a minute since last activity
if (!isset($_SESSION["last-activity"]) || time() - $_SESSION["last-activity"] > 1800) {
    header("Location: ./");
}

$_SESSION["last-activity"] = time();
$controller = new PanelController();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ewan's Polling System - User Panel</title>
</head>
<body>
    <h1>Welcome, <?php echo $controller->model->user->firstName . " " . $controller->model->user->lastName; ?>!</h1>
    
    <?php

        if (array_key_exists("sign-out", $_POST)) {
            signOut();
        }

    ?>

    <form method="post">
        <button name="sign-out" id="sign-out">Sign Out</button>
    </form>

    <?php $controller->invoke(); ?>
    
</body>
</html>

