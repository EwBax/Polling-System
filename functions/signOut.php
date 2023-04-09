<?php

function signOut() {
    session_destroy();
    header("Location: ./");

}

?>