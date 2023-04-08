<?php
require_once("../classes/user.php");
session_start();

$user = unserialize($_SESSION["user"]);

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
    <h1>Welcome, <?php echo $user->firstName . " " . $user->lastName; ?>!</h1>

    <?php echo $user->isAdmin; ?>
    
</body>
</html>