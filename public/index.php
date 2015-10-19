<?php

    /* Edinote controller for main page */

    require("../includes/config.php");

    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?"
                                , $_SESSION["id"])[0]['username'] . "/";
                                
    $_SESSION["usrdir"] = $usrdir;

    $files = query("SELECT fileid, file, tag1, tag2, tag3 FROM files 
                    WHERE id = ? ORDER BY LOWER(file)", $_SESSION["id"]);

    render("main.php", ["files" => $files, "title" => "<Edinote>"]);

?>
