<?php

    require(__DIR__ . "/../includes/config.php");

    if (empty($_POST["tagId"])) {
        echo 1;
        exit;
    }

    $fileId = substr($_POST["tagId"], -17);
    $tag = substr($_POST["tagId"], 0, 4);
    $response = [$fileId, $tag];

    // using $tag as a query parameter doesn't work here!
    $rmtag = query("UPDATE files SET {$tag} = NULL WHERE id = ? AND fileid = ?", $_SESSION["id"], $fileId);

    if ($rmtag !== false) {
        echo json_encode($response);
    }
    else {
        echo 2;
    }

?>
