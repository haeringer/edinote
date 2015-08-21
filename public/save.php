<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO code duplicate (index.php)!
    $usrdir = "../data/";

    $filename = $_POST["filename"];
    $contents = $_POST["contents"];

    // ensure file exists
    if (empty($filename))
    {
        http_response_code(400);
        exit;
    }


    $whatever = file_put_contents($usrdir.$filename, $contents) or die("can't open file");

?>
