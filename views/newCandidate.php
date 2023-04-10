<?php

include_once("functions/validation.php");

$firstName = $lastName = "";
$firstNameErr = $lastNameErr = "";


if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST["first-name"])) {

    $_SESSION["last-activity"] = time();

    // Validating first and last name
    $firstName = ucfirst(test_input($_POST["first-name"]));
    // TODO implement capitalizing first letter of names
    if (!validateName($firstName)) {
        $firstNameErr = "Only letters allowed. Name must be between 1 and 20 characters long.";
    }

    $lastName = ucfirst(test_input($_POST["last-name"]));
    if (!validateName($lastName)) {
        $lastNameErr = "Only letters allowed. Name must be between 1 and 20 characters long.";
    }

}

?>

    <h3>New Candidate</h3>

    <form action="panel" method="post">

        <label for="first-name">First Name: </label> 
        <input type="text" name="first-name" id="first-name" required="required" value="<?php echo $firstName ?>" placeholder="first name"> <?php echo $firstNameErr ?>
        <br>
        <br>
        <label for="last-name">Last Name: </label>
        <input type="text" name="last-name" id="last-name" required="required" value="<?php echo $lastName ?>" placeholder="last name"> <?php echo $lastNameErr ?>
        <br>
        <br>
        <input type="hidden" name="new-candidate" value="new-candidate">
        <button type="submit">Submit</button>

    </form>