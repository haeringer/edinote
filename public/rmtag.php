<?php

    require(__DIR__ . "/../includes/config.php");

    if (empty($_POST["tagId"])) {
        echo 1;
        exit;
    }

    $fileId = substr($_POST["tagId"], -17);
    $tag = substr($_POST["tagId"], 0, 4);

    // using $tag as a query parameter doesn't work here!
    query("UPDATE files SET {$tag} = NULL WHERE id = ? AND fileid = ?", $_SESSION["id"], $fileId);

    $response = [$fileId, $tag];
    echo json_encode($response);

?>
