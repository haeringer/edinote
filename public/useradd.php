<?php

    require("../includes/config.php");

    $rval = NULL;
    $name = $_POST["name"];
    $pw = $_POST["pw"];
    $adm = $_POST["adm"];

    if ($_SESSION['admin'] === 'false') {
        // user is not admin
        $rval = 1;
    }
    else {
        if (($name === NULL) || ($pw === NULL)) {
            // not all fields have been filled
            $rval = 2;
        }
        else {
            if (query("INSERT INTO users (username, hash, admin, demo
                , viewmode, defaultext) VALUES (?, ?, ?, 'false'
                , 'false', 'md')", $name, crypt($pw), $adm) !== false)
            {
                // user was successfully added to db; create user directory
                if (mkdir(DATADIR . $name) !== false)
                {
                    // success
                    $rval = 0;
                } else {
                    // could not create directory
                    $rval = 4;
                }
            } else {
                // username already exists
                $rval = 3;
            }
        }
    }

    // build array for ajax response
    $response = [
        "rval" => $rval
    ];

    // spit out content as json
    header("Content-type: application/json");
    echo json_encode($response);

?>
