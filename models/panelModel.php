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

    public function searchForCandidate($firstName, $lastName) {

        $connection = connect();

        $sql = "SELECT * FROM candidate WHERE first_name='$firstName' AND last_name='$lastName';";
        $result = $connection->query($sql);

        return $result->num_rows > 0;


    }

    public function removeCandidate($firstName, $lastName) {

        $connection = connect();

        $sql = "DELETE FROM candidate WHERE first_name='$firstName' AND last_name='$lastName';";
        $result = $connection->query($sql);

        $connection->close();

        return $result;

    }

    public function searchForUser($username) {

        $connection = connect();

        $sql = "SELECT * FROM login_credential WHERE username='$username';";
        $result = $connection->query($sql);

        $connection->close();

        return $result->num_rows > 0;

    }

    public function getUserDetails($username) {

        $connection = connect();

        // Query to get user details including whether they are an admin and have voted
        $sql = "SELECT first_name, last_name, is_admin, candidate_id, user.user_id FROM user INNER JOIN login_credential ON user.user_id=login_credential.user_id LEFT JOIN vote ON user.user_id=vote.user_id WHERE username='$username';";
        $result = $connection->query($sql);

        $connection->close();

        return $result->fetch_array();

    }

    public function updateUserDetails($firstName, $lastName, $username, $isAdmin, $userID) {
        $connection = connect();

        // updating the user and login_credentials tables
        $sql = "UPDATE user SET first_name='$firstName', last_name='$lastName' WHERE user_id='$userID'; UPDATE login_credential SET username='$username', is_admin='$isAdmin' WHERE user_id='$userID';";
        $result = $connection->multi_query($sql);

        $connection->close();

        return $result;
    }

    public function deleteUserVote($userID) {
        $connection = connect();

        $sql = "DELETE FROM vote WHERE user_id='$userID'";
        $result = $connection->query($sql);

        $connection->close();

        return $result;
    }

}

?>