<?php

    require("../includes/config.php");

    $rval = NULL;
    $name = $_POST["name"];
    $dir = DATADIR . $name;

    if ($_SESSION['admin'] === 'false') {
        // user is not admin
        $rval = 1;
    }
    else {
        if ($name === "Select...") {
            // no user selected
            $rval = 2;
        }
        else {
            $del = query("DELETE FROM users WHERE username = ?", $name);

            if ($del === false)
            {
                // something went wrong
                $rval = 3;
            } else {
                // user was successfully deleted from db;
                // delete user directory plus containing files

                foreach(scandir($dir) as $file) {
                    if ('.' === $file || '..' === $file) continue;
                    if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
                    else unlink("$dir/$file");
                }
                $rm = rmdir($dir);

                if ($rm === false)
                {
                    // could not delete directory
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
