<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $rval = NULL;
    $filename = NULL;
    $contents = $_POST["contents"];
    $save_as = $_POST["save_as"];
    $rename = $_POST["rename"];
    $fileId = uniqid('fid_');

    // if save.php was called with an empty filename, return 1
    if (empty($_POST["filename"])) {
        $rval = 1;
    }
    else {
        $filename = $_POST["filename"];
    }

    if ($filename !== NULL && $save_as === 'false') {
        // write contents to file (overwrite file safely without asking, because
        // save.php was called from the same file)
        $return = file_put_contents($usrdir.$filename, $contents);

        // return values to calling js function
        if ($return !== false) {
            // writing to file was successful
            $rval = 0;
        }
    }
    else if ($filename !== NULL && $save_as === 'true') {
        // save.php was called from save-as form (hence from a non-existing
        // file), therefore check if typed-in name does already exist.
        // If not write to file, if yes return error
        $files_arr = query("SELECT file FROM files WHERE id = ?", $_SESSION["id"]);

        $files = [];
        for ($i = 0; $i < sizeof($files_arr); $i++) {
            $files[$i] = $files_arr[$i]['file'];
        }

        if (in_array($filename, $files)) {
            $rval = 2;
        }
        else {
            // write contents to a new file
            $return = file_put_contents($usrdir.$filename, $contents);

            // return values to calling js function
            if ($return !== false) {

                // add new file name to database
                $inserted = query("INSERT INTO files (fileid, id, file, tag1, tag2, tag3)
                    VALUES (?, ?, ?, NULL, NULL, NULL)", $fileId, $_SESSION["id"], $filename);

                if ($inserted !== false) {
                    // writing to file and database was successful
                    $rval = 0;
                }
                else {
                    // writing to database was unsuccessful
                    $rval = 3;
                }
            }
        }
    }

    // json response
    $response = [
        "rval" => $rval,
        "fileId" => $fileId
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
