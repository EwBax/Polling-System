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
        if (array_key_exists("new-candidate", $_POST) || (isset($_POST["first-name"]) && isset($_POST["last-name"]))) {
            if (isset($_POST["first-name"]) && validateName($_POST["first-name"]) && validateName($_POST["last-name"])) {
                if ($this->model->registerCandidate($_POST["first-name"], $_POST["last-name"])) {
                    echo "<p>New candidate registered.</p>";
                } else {
                    echo "<p>A candidate by that name is already registered.</p>";
                }
            }
            
            include 'views/new-candidate.php';
            
            
        }

            // if (!$this->model->checkUserVote()) {
            //     $_SESSION["candidates"] = serialize($this->model->getCandidates());
            //     include 'views/vote.php';
            // } else {
            //     echo "You have already voted in this poll. You can only vote once.";
            // }
        

        
    }

    public function userPanel() {
        
        echo "<h2>User Panel</h2>\n";

        if (isset($_POST["candidate"])) {
            $this->model->castVote($_POST["candidate"]);
            echo "<p>Thank you for voting!</p>\n";
        } else if (!$this->model->checkUserVote()) {
            $_SESSION["candidateList"] = $this->model->getCandidates()->fetch_all();
            include 'views/vote.php';
        } else {
            echo "<p>You have already cast your vote, please contact an administrator if you would like to reset your vote.</p>\n";
        }
    }

    

}

?>