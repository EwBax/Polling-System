<?php

function connect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "inet_polling_system";

    // creating connection
    $connection = new mysqli($servername, $username, $password, $dbName);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;

}

?>
