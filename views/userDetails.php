<?php
include_once("functions/validation.php");

$firstNameErr = $lastNameErr = $usernameErr = "";
  
// Validating first and last name
if (isset($_POST["first-name"])) {
    $firstName = ucfirst(test_input($_POST["first-name"]));
    if (!validateName($firstName)) {
        $firstNameErr = "Only letters allowed. Name must be between 1 and 20 characters long.";
    }
}

if (isset($_POST["last-name"])) {
    $lastName = ucfirst(test_input($_POST["last-name"]));
    if (!validateName($lastName)) {
        $lastNameErr = "Only letters allowed. Name must be between 1 and 20 characters long.";
    }
}

if (isset($_POST["username"])) {
    $username = test_input($_POST['username']);
    if (!validateName($username)) {
        $usernameErr = "Invalid username. Must be between 4-20 characters long, and only contain letters and numbers.";
    }
}


?>


<br>

<h4>User <?php echo $username ?> Information:</h4>

<form action="" method="POST">
        <label for="first_name">First Name: </label>
        <input type="text" name="first-name" id="first-name" value="<?php echo $firstName; ?>" placeholder="first name" required="required"> <?php echo $firstNameErr; ?>
        <br>
        <br>
        <label for="last_name">Last Name: </label>
        <input type="text" name="last-name" id="last-name" value="<?php echo $lastName; ?>" placeholder="last name" required="required"> <?php echo $lastNameErr; ?>
        <br>
        <br>
        <label for="username">Username: </label>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>" placeholder="username" required="required"> <?php echo $usernameErr; ?>
        <br>
        <br>
        <label for="is-admin">Is administrator:</label>
        <input type="checkbox" name="is-admin" <?php if ($isAdmin) {echo "checked"; }?> value="1">
        <br>
        <br>
        <input type="hidden" name="edit-user" value="edit-user">
        <?php 
        if ($hasVoted) {
        ?>
            <button type="submit" name="delete-vote">Delete User's Vote</button>
            <br>
            <br>
        <?php
        }
        ?>
        <button type="submit" name="update-details" id="update-details">Update User Information</button>
    </form>