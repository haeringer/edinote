<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $filename = $_POST["filename"];

    // delete filename from database
    if (query("DELETE FROM files WHERE id = ? AND file = ?", $_SESSION["id"], $filename) === false) {
        echo 1;
        exit;
    }
    // delete file from file system
    if (unlink($usrdir.$filename) === false) {
        echo 2;
    }
    else {
        echo 0;
    }

?>
