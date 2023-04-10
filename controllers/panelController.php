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

        // Checking if new-candidate button has been pressed, or if first or last name has been submitted
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
        ?>

        <!-- HTML displaying top two candidates -->
        <h5>The two candidates with the most votes are:</h5>

        <blockquote><?php echo $topTwo[0][1] ?>, with <?php echo $topTwo[0][0] ?> vote(s).</blockquote>
        <blockquote><?php echo $topTwo[1][1] ?>, with <?php echo $topTwo[1][0] ?> vote(s).</blockquote>

        <?php

    }

    public function displayBottomCandidates() {
        $bottomCandidates = $this->model->getBottomCandidates();

        ?>
            <h5>The following candidate(s) had the least number of votes with <?php echo $bottomCandidates[0][0] ?> total vote(s):</h5>
        <?php

        foreach ($bottomCandidates as &$row) {
            ?>
            <blockquote><?php echo $row[1] ?></blockquote>
            <?php
        }
    }

    public function displayAllCandidates() {
        $allCandidates = $this->model->getAllCandidates();

        ?>
        <h5>The voting results for all candidates in alphabetical order is as follows:</h5>
        <?php

        foreach ($allCandidates as &$row) {
            ?>
            <blockquote><?php echo $row[1] ?>, with <?php echo $row[0] ?> vote(s).</blockquote>
            <?php
        }
    }

    public function displayWinner() {
        $winner = $this->model->getWinner();

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
    }

    

}

?>