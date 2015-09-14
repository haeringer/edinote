<?php

    /* controller for main */

    require("../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    // scan user directory for files and use array_diff() to remove the dots
    // $files = array_diff(scandir($usrdir), array('..', '.'));
    // --> instead of scandir() (above), query database
    $files = query("SELECT fileid, file, tag1, tag2, tag3 FROM files WHERE id = ?", $_SESSION["id"]);

    // var_dump($files_arr);
    //
    // $files = [];
    // for ($i = 0; $i < sizeof($files_arr); $i++) {
    //     $files[$i] = $files_arr[$i]['file'];
    // }

    render("main.php", ["files" => $files,/* "user" => $user, "usrdir" => $usrdir,*/ "title" => "<Edinote>"]);
    // require("../templates/temp.php");
?>
