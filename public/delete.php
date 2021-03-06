<?php

    require("../includes/config.php");

    $usrdir = $_SESSION['usrdir'];
    $filename = $_POST["filename"];
    $rval = NULL;

    // delete filename from database
    if (query("DELETE FROM files WHERE id = ? AND file = ?"
                , $_SESSION["id"], $filename) !== false)
    {
        // delete file from file system
        if (unlink($usrdir.$filename) !== false)
        {
            $rval = 0;
        }
        else {
            $rval = 2;
        }
    }
    else {
        $rval = 1;
    }

     // json response
    $response = [
        "rval" => $rval
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
