<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $filename = $_POST["filename"];
    $contents = $_POST["contents"];

    // write contents to file
    $saved = file_put_contents($usrdir.$filename, $contents);

    // error checking
    if ($saved === false)
    {
        http_response_code(503);
        exit;
    }

?>
