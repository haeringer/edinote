<?php

    require(__DIR__ . "/../includes/config.php");

    // ensure proper usage
    if (empty($_GET["$filename"]))
    {
        http_response_code(400);
        exit;
    }

    $filename = $_GET["filename"]);

    // TODO variable $usrdir
    $content = file_get_contents("../data".$filename);
    if ($content === false)
    {
        http_response_code(503);
        exit;
    }


    header("Content-type: application/json");
    // echo htmlentities(file_get_contents($usrdir."/markdown.md"));
    echo json_encode($content);

?>
