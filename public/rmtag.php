<?php

    require(__DIR__ . "/../includes/config.php");

    $rval = NULL;
    
    if (empty($_POST["tagId"])) {
        $rval = 1;
        exit;
    }

    $fileId = substr($_POST["tagId"], -17);
    $tag = substr($_POST["tagId"], 0, 4);

    // using $tag within query because it doesn't work as a parameter
    $rmtag = query("UPDATE files SET {$tag} = NULL WHERE id = ? AND fileid = ?"
                    , $_SESSION["id"], $fileId);

    if ($rmtag !== false) {
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
