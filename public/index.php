<?php

    /* controller for main */

    require("../includes/config.php");

    $user = query("SELECT username FROM users WHERE id = ?", $_SESSION["id"]);


    $usrdir = "../data";
    // scan user directory for files and use array_diff to remove the dots
    $files = array_diff(scandir($usrdir), array('..', '.'));

    // var_dump($files);


    render("main.php", ["files" => $files, "usrdir" => $usrdir, "title" => "Main"]);
    // require("../templates/temp.php");
?>
