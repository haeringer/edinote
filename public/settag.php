<?php

    require("../includes/config.php");

    $filename = $_POST["filename"];
    $fileId = $_POST["fileId"];
    $tagged = false;
    $tag_num = NULL;
    $tagId = NULL;
    $rval = NULL;

    if (empty($_POST["tag"])) {
        $rval = 1;
    } else {
        $tag = htmlspecialchars($_POST["tag"]);
    }

    // get tag slots
    $tags = query("SELECT tag1,tag2,tag3 FROM files WHERE id = ? AND file = ?",
            $_SESSION["id"], $filename);
    // try all tag slots
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
    } else {
        $rval = 3;
    }

    if ($rval !== 3 && $tagged !== false) {
        $rval = 0;
        $tagId = $tag_num . '_' . $fileId;
    }
    else if ($rval !== 3 && $tagged === false) {
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
