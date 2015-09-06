<?php

    require(__DIR__ . "/../includes/config.php");

    if (empty($_POST["filename"])) {
        echo 1;
        exit;
    }

    $filename = $_POST["filename"];
    $tag = $_POST["tag"];

    // check which tag slot is free
    $tags = query("SELECT tag1,tag2,tag3 FROM files WHERE id = ? AND file = ?",
            $_SESSION["id"], $filename);

    echo var_dump($tags);

    for ($i = 0; $i < sizeof($tags); $i++) {
        if ($tags[0][$i] === NULL) {
            $tagged = query("UPDATE files SET tag1 = ? WHERE id = ? AND file = ?",
                        $_POST["tag"], $_SESSION["id"], $filename);

            if ($tagged !== false) {
                echo 0;
            }
            else {
                echo 2;
            }
        }
    }


?>
