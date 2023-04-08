<?php

ini_set( "session.gc_maxlifetime", 1440 );
session_start();

require_once("controllers/loginController.php");
$loginController = new LoginController();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ewan's Polling System</title>
</head>
<body>

    <h1>Welcome to Ewan's Polling System</h1>
    

    <h2>Please log in or register below</h2>


    <?php

    $loginController->invoke();

    ?>

</body>
</html>





