<?php

    require(__DIR__ . "/../includes/config.php");

    // if save.php was called with an empty filename, return 0 to js
    if (empty($_POST["filename"])) {
        echo 1;
        exit;
    }

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $filename = $_POST["filename"];
    $contents = $_POST["contents"];
    $save_as = $_POST["save_as"];

    if ($save_as === '0') {
        // write contents to file (overwrite file safely without asking, because
        // save.php was called from the same file)
        $return = file_put_contents($usrdir.$filename, $contents);

        // return values to calling js function
        if ($return !== false) {
            // writing to file was successful
            echo 0;
        }
    }
    else {
        // save.php was called from save-as form (hence from a non-existing
        // file), therefore check if typed-in name does already exist.
        // If not write to file, if yes return error
        $files_arr = query("SELECT files FROM files WHERE id = ?", $_SESSION["id"]);

        $files = [];
        for ($i = 0; $i < sizeof($files_arr); $i++) {
            $files[$i] = $files_arr[$i]['files'];
        }

        if (in_array($filename, $files)) {
            echo 2;
        }
        else {
            // write contents to a new file
            $return = file_put_contents($usrdir.$filename, $contents);

            // add new file name to database
            $file_db = query("INSERT INTO files (id, files) VALUES (?, ?)", $_SESSION["id"], $filename);

            // return values to calling js function
            if ($return !== false) {
                // writing to file was successful
                echo 0;
            }
        }
    }

?>
