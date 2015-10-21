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
            $insrt = query("INSERT INTO users (username, hash, admin, demo
                            , viewmode, defaultext)
                            VALUES (?, ?, ?, 'false', 'false', 'md')"
                            , $name, crypt($pw), $adm);

            if ($insrt === false)
            {
                // username already exists
                $rval = 3;
            } else {
                // user was successfully added to db; create user directory
                $dir = mkdir(DATADIR . $name);

                if ($dir === false)
                {
                    // could not create directory
                    $rval = 4;
                } else {
                    // success
                    $rval = 0;
                }
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
