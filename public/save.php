<?php

    require("../includes/config.php");

    $usrdir = $_SESSION['usrdir'];
    $rval = NULL;
    $fileEl = NULL;
    $filename = NULL;
    $filename_old = $_POST["filename_old"];
    $contents = $_POST["contents"];
    $save_as = $_POST["save_as"];
    $rename = $_POST["rename"];
    $fileId = uniqid('fid_');

    // if save.php was called with an empty filename, return 1
    if (empty($_POST["filename"])) {
        $rval = 1;
    } else {
        $filename = htmlspecialchars($_POST["filename"]);
    }

    if ($filename !== NULL && $save_as === 'false') {
        // write contents to file (overwrite file safely without asking,
        // because save.php was called from the same file)
        $return = file_put_contents($usrdir.$filename, $contents);

        if ($return !== false) {
            // writing to file was successful
            $rval = 0;
        }
    }
    else if ($filename !== NULL && $save_as === 'true') {
        // save.php was called from save-as form (hence from a non-existing
        // file), therefore check if typed-in name does already exist.
        // If not write to file, if yes return error
        $files_arr = query("SELECT file FROM files WHERE id = ?"
                            , $_SESSION["id"]);
        $files = [];
        for ($i = 0; $i < sizeof($files_arr); $i++) {
            $files[$i] = $files_arr[$i]['file'];
        }

        if (in_array($filename, $files)) {
            $rval = 2;
        }
        else {
            if ($rename === 'false') {
                // write contents to a new file
                $return = file_put_contents($usrdir.$filename, $contents);

                // return values to calling js function
                if ($return !== false) {

                    // add new file name to database
                    if (query("INSERT INTO files (fileid, id, file, tag1
                        , tag2, tag3) VALUES (?, ?, ?, NULL, NULL, NULL)"
                        , $fileId, $_SESSION["id"], $filename) !== false) {

                        // writing to file and database was successful
                        $rval = 0;

                        // include html markup for new file in JSON response
                        ob_start();
                        include '../templates/file_template.php';
                        $fileEl = ob_get_clean();
                    }
                    else {
                        // writing to database was unsuccessful
                        $rval = 3;
                    }
                }
            }
            else if ($rename === 'true') {

                // rename file in file system
                if (rename($usrdir.$filename_old,$usrdir.$filename) !== false) {

                    // rename file in database
                    if (query("UPDATE files SET file = ? WHERE id = ? AND
                            file = ?", $filename, $_SESSION["id"]
                            , $filename_old) !== false) {
                        // renaming file and writing to db was successful
                        $rval = 4;
                    }
                    else {
                        // writing to database was unsuccessful
                        $rval = 3;
                    }
                }
            }
        }
    }

    // json response
    $response = [
        "rval" => $rval,
        "fileId" => $fileId,
        "fileEl" => $fileEl
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
