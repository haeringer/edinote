<?php

    require(__DIR__ . "/../includes/config.php");

    $usrdir = $_SESSION['usrdir'];
    $filename = $_POST["filename"];
    $rval = NULL;

    // delete filename from database
    if (query("DELETE FROM files WHERE id = ? AND file = ?"
                , $_SESSION["id"], $filename) === false) {
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
