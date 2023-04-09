<?php

$candidateList = $_SESSION["candidateList"];
?>

    <h4>Cast your vote below!</h4>


    <form action="" method="post">
        <fieldset>
        <?php
        foreach ($candidateList as $row) {
        ?>
            <input type="radio" name="candidate" id="candidate<?php echo $row[0] ?>" value="<?php echo $row[0] ?>" required="required">
            <label for="candidate<?php echo $row[0]?>"><?php echo $row[1] ?></label>
            <br>
        <?php
        }
        ?>
        </fieldset>
        <button type="submit">Submit Vote</button>
    </form>