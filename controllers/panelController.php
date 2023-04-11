<?php
require_once("models/panelModel.php");
require_once("functions/validation.php");

class PanelController {

    public $model;

    public function __construct() {
        
        $this->model = new PanelModel();

    }

    public function invoke() {
        
        $_SESSION["last-activity"] = time();

        if ($this->model->user->isAdmin) {
            include 'views/adminPanel.php';
        } else {
            $this->userPanel();
        }

        // Checking if new-candidate button has been pressed
        if (array_key_exists("new-candidate", $_POST)) {

            if (isset($_POST["first-name"]) && validateName($_POST["first-name"]) && validateName($_POST["last-name"])) {
                if ($this->model->registerCandidate($_POST["first-name"], $_POST["last-name"])) {
                    echo "<p>New candidate registered.</p>";
                } else {
                    echo "<p>A candidate by that name is already registered.</p>";
                }
            }
            
            include 'views/newCandidate.php';
        
        } else if (array_key_exists("view-results", $_POST)) {

            include 'views/results.php';
            if (array_key_exists("top-two", $_POST)) {
                $this->displayTopTwoCandidates();
            } else if (array_key_exists("bottom", $_POST)) {
                $this->displayBottomCandidates();
            } else if (array_key_exists("all-candidates", $_POST)) {
                $this->displayAllCandidates();
            } else if (array_key_exists("winner", $_POST)) {
                $this->displayWinner();
            } 

        } else if (array_key_exists("remove-candidate", $_POST)) {

            include 'views/removeCandidate.php';

            // Checking if a candidate has been entered
            if (isset($_POST["first-name"]) && validateName($_POST["first-name"]) && validateName($_POST["last-name"])) {

                $firstName = $_POST["first-name"];
                $lastName = $_POST["last-name"];

                // Checking if that candidate exists
                if ($this->model->searchForCandidate($firstName, $lastName)) {

                    // Removing them
                    if ($this->model->removeCandidate($firstName, $lastName)) {
                        echo "<p>Candidate $firstName $lastName has been removed from the poll.</p>";
                    } else {
                        echo "Something went wrong when trying to remove candidate $firstName $lastName form the poll.";
                    }

                // Candidate does not exist
                } else {
                    echo "<p>There is no registered candidate by this name, please double check your spelling and search again.</p>";
                }
            }

        } else if (array_key_exists("edit-user", $_POST)) {
            
            echo "<h3>Edit a User</h3>";
            
            include 'views/searchForUser.php';

            if (isset($_POST["username"]) && validateUsername($_POST["username"])) {
                $username = $_POST["username"];

                if ($this->model->searchForUser($username)) {
                    $userDetails = $this->model->getUserDetails($username);
                    $firstName = $userDetails[0];
                    $lastName = $userDetails[1];
                    $isAdmin = $userDetails[2];
                    $hasVoted = ($userDetails[3] != null);
                    $userID = $userDetails[4];
                    if (array_key_exists("update-details", $_POST)) {

                        $isAdmin = array_key_exists("is-admin", $_POST);

                        if ($this->model->updateUserDetails($_POST["first-name"], $_POST["last-name"], $_POST["username"], $isAdmin, $userID)) {
                            echo "<p>User $username's details have been updated.</p>";
                        }
                    } else {
                        include 'views/userDetails.php';
                        if (array_key_exists("delete-vote", $_POST)) {
                            $this->model->deleteUserVote($userID);
                            echo "<p>User's vote was deleted.</p>";
                        }
                    }
                } else {
                    echo "<p>There is no user with that username</p>";
                }
            }

        }
        
    }

    public function userPanel() {
        
        echo "<h2>User Panel</h2>\n";

        // If the user has not voted yet
        if (!$this->model->checkUserVote()) {
            // If a candidate has been selected
            if (isset($_POST["candidate"])) {
                $this->model->castVote($_POST["candidate"]);
                echo "<p>Thank you for voting!</p>\n";
            } else {
                // If a candidate has not yet been selected
                $_SESSION["candidateList"] = $this->model->getCandidates()->fetch_all();
                include 'views/vote.php';
            }
        } else {
            // If the user has already cast a vote
            echo "<p>You have already cast your vote, please contact an administrator if you would like to reset your vote.</p>\n";
        }
    }

    public function displayTopTwoCandidates() {
        $topTwo = $this->model->getTopTwoCandidates();

        if (sizeof($topTwo) > 0) {
        ?>

            <!-- HTML displaying top two candidates -->
            <h5>The two candidates with the most votes are:</h5>

            <blockquote><?php echo $topTwo[0][1] ?>, with <?php echo $topTwo[0][0] ?> vote(s).</blockquote>
            <blockquote><?php echo $topTwo[1][1] ?>, with <?php echo $topTwo[1][0] ?> vote(s).</blockquote>

        <?php
        } else {
            echo "<p>No votes have been cast yet!</p>";
        }

    }

    public function displayBottomCandidates() {
        $bottomCandidates = $this->model->getBottomCandidates();
        if (sizeof($bottomCandidates) > 0) {
        ?>
                <h5>The following candidate(s) had the least number of votes with <?php echo $bottomCandidates[0][0] ?> total vote(s):</h5>
            <?php

            foreach ($bottomCandidates as &$row) {
                ?>
                <blockquote><?php echo $row[1] ?></blockquote>
                <?php
            }
        } else {
            echo "<p>There are no registered candidates!</p>";
        }
    }

    public function displayAllCandidates() {
        $allCandidates = $this->model->getAllCandidates();
        if (sizeof($allCandidates) > 0) {
        ?>
            <h5>The voting results for all candidates in alphabetical order is as follows:</h5>
            <?php

            foreach ($allCandidates as &$row) {
                ?>
                <blockquote><?php echo $row[1] ?>, with <?php echo $row[0] ?> vote(s).</blockquote>
                <?php
            }
        } else {
            echo "<p>There are no registered candidates!</p>";
        }
    }

    public function displayWinner() {
        $winner = $this->model->getWinner();
        if (sizeof($winner) > 0) {
            if (sizeof($winner) > 1) {
                ?>
                <h5>There is a tie for first place between the following candidates, with <?php echo $winner[0][0] ?> vote(s) each:</h5>
                <?php
                foreach ($winner as &$row) {
                    ?>
                    <blockquote><?php echo $row[1] ?></blockquote>
                    <?php
                }
            } else {
                ?>
                <h5>The winning candidate is <?php echo $winner[0][1] ?>, with <?php echo $winner[0][0] ?> vote(s)!</h5>
                <?php
            }
        } else {
            echo "<p>There are no registered candidates!</p>";
        }
    }

    

}

?>