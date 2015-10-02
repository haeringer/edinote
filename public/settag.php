<?php

    require(__DIR__ . "/../includes/config.php");

    $filename = $_POST["filename"];
    $fileId = $_POST["fileId"];
    $tag = $_POST["tag"];
    $tagged = false;
    $tag_num = NULL;
    $tagId = NULL;
    $rval = NULL;

    if (empty($_POST["filename"])) {
        $rval = 1;
    }

    // check which tag slot is free
    $tags = query("SELECT tag1,tag2,tag3 FROM files WHERE id = ? AND file = ?",
            $_SESSION["id"], $filename);

    // TODO - consolidate?! (loop)
    if ($tags[0]['tag1'] === NULL) {
        $tag_num = 'tag1';
        $tagged = query("UPDATE files SET tag1 = ? WHERE id = ? AND file = ?",
                    $_POST["tag"], $_SESSION["id"], $filename);
    }
    else if ($tags[0]['tag2'] === NULL) {
        $tag_num = 'tag2';
        $tagged = query("UPDATE files SET tag2 = ? WHERE id = ? AND file = ?",
                    $_POST["tag"], $_SESSION["id"], $filename);
    }
    else if ($tags[0]['tag3'] === NULL) {
        $tag_num = 'tag3';
        $tagged = query("UPDATE files SET tag3 = ? WHERE id = ? AND file = ?",
                    $_POST["tag"], $_SESSION["id"], $filename);
    }
    else {
        $rval = 3;
    }

    if ($tagged !== false) {
        $rval = 0;
        $tagId = $tag_num . '_' . $fileId;
    }
    else {
        $rval = 2;
    }

    // json response
    $response = [
        "rval" => $rval,
        "tagId" => $tagId
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
