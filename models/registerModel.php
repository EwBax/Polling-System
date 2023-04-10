<?php
include_once("functions/dbAdapter.php");

class RegisterModel {

    public $servername;
    public $username;
    public $password;
    public $dbName;

    // Function to register new user
    public function register() 
    {
        
        // if all form fields are filled out
        if (isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['first_name']) && isset($_REQUEST['last_name']) && isset($_REQUEST['email'])) {


            $username = $_REQUEST['username'];
            $password = $_REQUEST['password'];
            $first_name = $_REQUEST['first_name'];
            $last_name = $_REQUEST['last_name'];
            $email = $_REQUEST['email'];

            $connection = connect();

            // Creating user in db
            $sql = "INSERT INTO user (first_name, last_name, email) VALUES ('$first_name', '$last_name', '$email')";
            $result = $connection->query($sql);

            // if user was successfully created
            if ($result) {

                // Getting new user's user_id
                $sql = "SELECT user_id FROM user WHERE email='$email'";
                $result = $connection->query($sql);

                $user_id = $result->fetch_array();

                // Creating login_credential for new user
                $sql = "INSERT INTO login_credential (user_id, username, password) VALUES ('$user_id[0]', '$username', '$password')";
                $result = $connection->query($sql);

            }

            $connection->close();
            return $result;

        }
        
        // If the function does not return earlier than it failed to register the user.
        return false;

    }


    // Function to check if email exists in DB already
    public function checkEmail($email)
    {
        $connection = connect();
        $sql = "SELECT email FROM user WHERE email='$email'";
        $result = $connection->query($sql);
        $connection->close();
        return $result->num_rows > 0;

    }


    // Function to check if username exists in DB already
    public function checkUsername($username) 
    {

        $connection = connect();
        $sql = "SELECT username FROM login_credential WHERE username='$username'";
        $result = $connection->query($sql);
        $connection->close();

        return $result->num_rows > 0;

    }



}

?>