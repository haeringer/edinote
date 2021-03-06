<?php

    require("../includes/config.php");

    $usrdir = $_SESSION['usrdir'];
    $fileId = $_GET["fileId"];

    // getfile wasn't called properly with a file id
    if (empty($fileId))
    {
        http_response_code(400);
        exit;
    }

    // get name of file
    $filename = query("SELECT file FROM files WHERE fileid = ?"
                        , $fileId)[0]['file'];

    // extract content of file
    $content = file_get_contents($usrdir.$filename);

    // ensure content extraction did work
    if ($content === false)
    {
        http_response_code(503);
        exit;
    }

    // build array for ajax response
    $response = [
        "content" => $content,
        "filename" => $filename
    ];

    // spit out content as json
    header("Content-type: application/json");
    echo json_encode($response);

?>
