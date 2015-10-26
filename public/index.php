<?php

    /**
     * Edinote controller for main page
     *
     * Ben Haeringer
     * ben.haeringer@gmail.com
     *
     */

    require("../includes/config.php");

    // store user data directory in global SESSION variable
    $_SESSION["usrdir"] = DATADIR . query("SELECT username FROM users WHERE id = ?"
                                , $_SESSION["id"])[0]['username'] . "/";

    // fetch username for demo functionality
    $_SESSION["user"] = query("SELECT username FROM users WHERE id = ?"
                                , $_SESSION["id"])[0]['username'];

    // check if user has Edinote admin rights
    $_SESSION["admin"] = query("SELECT admin FROM users WHERE id = ?"
                    , $_SESSION["id"])[0]['admin'];
    $admin = $_SESSION["admin"];

    $users = NULL;
    if ($admin === "true") {
        $users = query("SELECT username FROM users");
    }

    // get file arrays from database
    $files = query("SELECT fileid, file, tag1, tag2, tag3 FROM files
                    WHERE id = ? ORDER BY LOWER(file)", $_SESSION["id"]);

    // array of filenames contained in db
    for ($i = 0; $i < sizeof($files); $i++) {
        $files_db[$i] = $files[$i]["file"];
    }

    // get actual files in user directory
    $files_dir = array_diff(scandir($_SESSION["usrdir"]), array('..', '.'));

    // compare actual files to files in db
    $diff = array_diff($files_dir, $files_db);
    if (!empty($diff)) {

        // if there are new files, write database entry for each new file item
        foreach ($diff as $item) {
            $fileId = uniqid('fid_');
            if (query("INSERT INTO files (fileid, id, file, tag1
                        , tag2, tag3) VALUES (?, ?, ?, NULL, NULL, NULL)"
                        , $fileId, $_SESSION["id"], $item) === false)
            {
                error_log('Could not add files to database');
            }
        }
        // update files array for later use
        $files = query("SELECT fileid, file, tag1, tag2, tag3 FROM files
                    WHERE id = ? ORDER BY LOWER(file)", $_SESSION["id"]);
    }

    render("main.php", ["files" => $files, "admin" => $admin, "users" => $users]);

?>
