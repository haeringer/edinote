<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO code duplicate (index.php)!
    $usrdir = "../data/";

    $filename = $_POST["filename"];
    $contents = $_POST["contents"];

    // write contents to file
    $saved = file_put_contents($usrdir.$filename, $contents) or die("can't open file");

    // error checking
    if ($saved === false)
    {
        http_response_code(503);
        exit;
    }

?>
