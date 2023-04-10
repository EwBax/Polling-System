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
            $result = $connection->query($sql);
            $connection->close();
            return $result;
        } catch (mysqli_sql_exception) {
            $connection->close();
            return false;
        }
    }

    public function checkUserVote() {

        $connection = connect();
        $sql = "SELECT * FROM vote WHERE user_id='$this->userID'";
        
        $result = $connection->query($sql);

        $connection->close();

        return ($result->num_rows > 0);
    }

    public function getCandidates() {
        $connection = connect();

        $sql = "SELECT candidate_id, CONCAT(first_name, ' ', last_name) AS name FROM candidate";
        $result = $connection->query($sql);

        $connection->close();
        return $result;
    }

    public function castVote($candidateID) {
        $connection = connect();

        $sql = "INSERT INTO vote VALUES ('$this->userID', '$candidateID')";
        $result = $connection->query($sql);
        $connection->close();
        return $result;

    }

    public function getTopTwoCandidates() {
        $connection = connect();

        // Query to get the 2 candidates with the most votes. Gets num of votes, and candidate name in that order
        $sql = "SELECT COUNT(user_id) AS num_votes, CONCAT(first_name, ' ', last_name) AS NAME FROM vote INNER JOIN candidate ON vote.candidate_id = candidate.candidate_id GROUP BY vote.candidate_id ORDER BY num_votes DESC LIMIT 2; ";
        $result =$connection->query($sql);

        $connection->close();

        return $result->fetch_all();

    }


    public function getBottomCandidates() {
        $connection = connect();

        // Query to get the candidate(s) with the lowest number of votes. Uses a subqeury to find the lowest number of votes, then returns all candidates who have that number of votes, in case it is a tie.
        $sql = "SELECT COUNT(user_id) AS num_votes, CONCAT(first_name, ' ', last_name) AS NAME FROM vote RIGHT JOIN candidate ON vote.candidate_id = candidate.candidate_id GROUP BY candidate.candidate_id HAVING num_votes =( SELECT COUNT(user_id) AS num_votes FROM vote RIGHT JOIN candidate ON vote.candidate_id = candidate.candidate_id GROUP BY candidate.candidate_id ORDER BY num_votes ASC LIMIT 1 ) ORDER BY candidate.last_name, candidate.first_name; ";
        $result = $connection->query($sql);

        $connection->close();

        return $result->fetch_all();
    }

    public function getAllCandidates() {
        $connection = connect();

        // Query to get number of votes for all candidates
        $sql = "SELECT COUNT(user_id) AS num_votes, CONCAT(first_name, ' ', last_name) AS name FROM vote RIGHT JOIN candidate ON vote.candidate_id=candidate.candidate_id GROUP BY candidate.candidate_id ORDER BY candidate.last_name, candidate.first_name;";
        $result = $connection->query($sql);

        $connection->close();

        return $result->fetch_all();
    }

    public function getWinner() {
        $connection = connect();

        // Same query as getting the bottom candidates but getting all candidates with the highest number of votes. In case there is a tie.
        $sql = "SELECT COUNT(user_id) AS num_votes, CONCAT(first_name, ' ', last_name) AS NAME FROM vote RIGHT JOIN candidate ON vote.candidate_id = candidate.candidate_id GROUP BY candidate.candidate_id HAVING num_votes =( SELECT COUNT(user_id) AS num_votes FROM vote RIGHT JOIN candidate ON vote.candidate_id = candidate.candidate_id GROUP BY candidate.candidate_id ORDER BY num_votes DESC LIMIT 1 ) ORDER BY candidate.last_name, candidate.first_name; ";

        $result = $connection->query($sql);

        $connection->close();

        return $result->fetch_all();
    }

}

?>