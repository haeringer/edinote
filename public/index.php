<?php

    /* controller for main */

    require("../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $files = query("SELECT fileid, file, tag1, tag2, tag3 FROM files WHERE id = ?", $_SESSION["id"]);

    render("main.php", ["files" => $files,/* "user" => $user, "usrdir" => $usrdir,*/ "title" => "<Edinote>"]);
    // require("../templates/temp.php");
?>
