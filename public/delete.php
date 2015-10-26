<?php

    require(__DIR__ . "/../includes/config.php");

    $usrdir = $_SESSION['usrdir'];
    $filename = $_POST["filename"];
    $rval = NULL;

    if ($_SESSION['user'] === 'demo' && $filename === '0_README.md') {
        $rval = 3;
    }
    else {
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
    }

     // json response
    $response = [
        "rval" => $rval
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
