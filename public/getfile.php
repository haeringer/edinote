<?php

    require(__DIR__ . "/../includes/config.php");

    // TODO make user variables globally available somehow
    $usrdir = DATADIR . query("SELECT username FROM users WHERE id = ?", $_SESSION["id"])[0]['username'] . "/";

    $filename = $_GET["filename"];

    // ensure file exists
    if (empty($filename))
    {
        http_response_code(400);
        exit;
    }

    // extract content of file
    $content = file_get_contents($usrdir.$filename);

    // ensure content extraction did work
    if ($content === false)
    {
        http_response_code(503);
        exit;
    }

    // spit out content as json
    header("Content-type: application/json");
    echo json_encode($content);

?>
