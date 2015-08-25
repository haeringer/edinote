<?php

    require(__DIR__ . "/../includes/config.php");

    // if save.php was called without an existing filename, return 0 to js
    if (empty($_POST["filename"])) {
        echo 0;
        exit;
    }

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $filename = $_POST["filename"];
    $contents = $_POST["contents"];

    // write contents to file
    $return = file_put_contents($usrdir.$filename, $contents);

    // return values to calling js function
    if ($return !== false) {
        // writing to file was successful
        echo 1;
    }
    else {
        // writing to file was unsuccessful for any reason
        echo 2;
    }

?>
