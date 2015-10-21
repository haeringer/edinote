<?php

    /**
     * Edinote controller for main page
     *
     * Ben Haeringer
     * ben.haeringer@gmail.com
     *
     */

    require("../includes/config.php");

    $_SESSION["usrdir"] = DATADIR . query("SELECT username FROM users WHERE id = ?"
                                , $_SESSION["id"])[0]['username'] . "/";
    
    $admin = query("SELECT admin FROM users WHERE id = ?"
                    , $_SESSION["id"])[0]['admin'];
    $_SESSION["admin"] = $admin;

    $files = query("SELECT fileid, file, tag1, tag2, tag3 FROM files 
                    WHERE id = ? ORDER BY LOWER(file)", $_SESSION["id"]);
    
    $users = NULL;
    if ($admin === "true") {
        $users = query("SELECT username FROM users");
    }

    render("main.php", ["files" => $files, "admin" => $admin, "users" => $users]);

?>
