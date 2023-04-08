<?php
require_once("classes/user.php");

class LoginModel {

    public function getlogin() {
        
        if (isset($_REQUEST['username']) && isset($_REQUEST['password']) ) {
            
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

            $sql = "SELECT username, password FROM login_credential WHERE username='" . $_REQUEST['username'] . "' AND password='" . $_REQUEST['password'] . "'";
            $result = $connection->query($sql);


            if ($result->num_rows > 0) {

                // Collecting user data from db
                $sql = "SELECT USER.user_id, first_name, last_name, email, username, is_admin FROM USER INNER JOIN login_credential ON USER.user_id=login_credential.user_id WHERE username='$_REQUEST[username]'";
                $result = $connection->query($sql);
                $userData = $result->fetch_array();

                // Creating user object and storing in session
                $user = new User($userData[0], $userData[1], $userData[2], $userData[3], $userData[4], $userData[5]);
                $_SESSION["user"] = serialize($user);

                // Creating session variable to represent being logged in
                $_SESSION["logged_in"] = true;

                return true;
            } else {
                echo "<p>Invalid username/password combination.</p>\n\n\t";
                return false;
            }

        }

    }

}

?>