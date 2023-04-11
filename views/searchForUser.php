<?php

include_once("functions/validation.php");

$username = "";
$usernameErr = "";


if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST["username"])) {

    $_SESSION["last-activity"] = time();

    $username = test_input($_POST['username']);
    if (!validateName($username)) {
        $usernameErr = "Invalid username. Must be between 4-20 characters long, and only contain letters and numbers.";
    }
    
}

?>

<p>Enter the username of the user to edit: </p>

<form action="" method="post">
    <label for="username">Username: </label> 
    <input type="text" name="username" id="username" required="required" value="<?php echo $username ?>" placeholder="username"> <?php echo $usernameErr ?>
    <br>
    <br>
    <input type="hidden" name="edit-user" value="edit-user">
    <button type="submit">Submit</button>
</form>