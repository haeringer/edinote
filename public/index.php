<?php

    /* controller for main */

    require("../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    // scan user directory for files and use array_diff() to remove the dots
    // $files = array_diff(scandir($usrdir), array('..', '.'));
    // --> instead of scandir() (above), query database
    $files_arr = query("SELECT file FROM files WHERE id = ?", $_SESSION["id"]);

    $files = [];
    for ($i = 0; $i < sizeof($files_arr); $i++) {
        $files[$i] = $files_arr[$i]['file'];
    }

    render("main.php", ["files" => $files, /* "user" => $user, "usrdir" => $usrdir,*/ "title" => "Main"]);
    // require("../templates/temp.php");
?>
