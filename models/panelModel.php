<?php

require_once("functions/dbAdapter.php");
require_once("classes/user.php");


class PanelModel {

    public $user;
    public $userID;

    public function __construct() {
        $this->user = unserialize($_SESSION["user"]);
        $this->userID = $this->user->userID;
    }

    public function registerCandidate($firstName, $lastName) {
        $connection = connect();
        
        $sql = "INSERT INTO candidate (first_name, last_name) VALUES ('$firstName', '$lastName')";
        try {
            return $connection->query($sql);
        } catch (mysqli_sql_exception) {
            return false;
        }
    }

    public function checkUserVote() {

        $connection = connect();
        $sql = "SELECT * FROM vote WHERE user_id='$this->userID'";
        
        $result = $connection->query($sql);

        return ($result->num_rows > 0);
    }

    public function getCandidates() {
        $connection = connect();

        $sql = "SELECT candidate_id, CONCAT(first_name, ' ', last_name) AS name FROM candidate";
        return $connection->query($sql);
    }

    public function castVote($candidateID) {
        $connection = connect();

        $sql = "INSERT INTO vote VALUES ('$this->userID', '$candidateID')";
        return $connection->query($sql);

    }

}

?>