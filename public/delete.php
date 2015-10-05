<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $filename = $_POST["filename"];
    $rval = NULL;

    // delete filename from database
    if (query("DELETE FROM files WHERE id = ? AND file = ?", $_SESSION["id"], $filename) === false) {
        $rval = 1;
        exit;
    }
    // delete file from file system
    if (unlink($usrdir.$filename) === false) {
        $rval = 2;
    }
    else {
        $rval = 0;
    }
    
     // json response
    $response = [
        "rval" => $rval
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
