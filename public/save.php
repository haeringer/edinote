<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO code duplicate (index.php)!
    $usrdir = "../data/";

    $filename = $_POST["filename"];
    $contents = $_POST["contents"];

    // write contents to file
    file_put_contents($usrdir.$filename, $contents) or die("can't open file");

?>
