<?php
include_once("functions/dbAdapter.php");

class RegisterModel {

    public $servername;
    public $username;
    public $password;
    public $dbName;
    public $connection;

    // Establishing DB connection in constructor
    public function __construct()
    {
        $this->connection = connect();
    }



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

            // Creating user in db
            $sql = "INSERT INTO user (first_name, last_name, email) VALUES ('$first_name', '$last_name', '$email')";
            $result = $this->connection->query($sql);

            // if user was successfully created
            if ((bool)$result) {
                // Getting new user's user_id
                $sql = "SELECT user_id FROM user WHERE email='$email'";
                $result = $this->connection->query($sql);

                $user_id = $result->fetch_array();

                // Creating login_credential for new user
                $sql = "INSERT INTO login_credential (user_id, username, password) VALUES ('$user_id[0]', '$username', '$password')";
                return $this->connection->query($sql);
            }

        }

    }


    // Function to check if email exists in DB already
    public function checkEmail($email)
    {

        $sql = "SELECT email FROM user WHERE email='$email'";
        $result = $this->connection->query($sql);

        return $result->num_rows > 0;

    }


    // Function to check if username exists in DB already
    public function checkUsername($username) 
    {

        $sql = "SELECT username FROM login_credential WHERE username='$username'";
        $result = $this->connection->query($sql);

        return $result->num_rows > 0;

    }



}

?>