<?php

    require(__DIR__ . "/../includes/config.php");

    $rval = NULL;

    if (empty($_POST["tagId"])) {
        $rval = 1;
        exit;
    }

    // extract the last 17 digits to get the file ID
    $fileId = substr($_POST["tagId"], -17);
    // extract first 4 digits to get tag number
    $tag = substr($_POST["tagId"], 0, 4);

    // use $tag within query because it doesn't work as parameter
    if (query("UPDATE files SET {$tag} = NULL WHERE id = ? AND fileid = ?"
                , $_SESSION["id"], $fileId) !== false) {
        $rval = 0;
    }
    else {
        $rval = 2;
    }

    $response = [
        "rval" => $rval,
        "file" => $fileId,
        "tag" => $tag
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
