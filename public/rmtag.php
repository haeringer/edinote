<?php

    require(__DIR__ . "/../includes/config.php");

    if (empty($_POST["tagId"])) {
        echo 1;
        exit;
    }

    $fileId = substr($_POST["tagId"], -17);
    $tag = substr($_POST["tagId"], 0, 4);

    $response = [$fileId, $tag];
    echo json_encode($response);

?>
