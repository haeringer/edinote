<?php

    require(__DIR__ . "/../includes/config.php");

    if (empty($_POST["filename"])) {
        echo 1;
        exit;
    }

    $filename = $_POST["filename"];
    $tag = $_POST["tag"];

    $tagged = query("UPDATE files SET tags = ? WHERE id = ? AND file = ?",
                $_POST["tag"], $_SESSION["id"], $filename);

    if ($tagged !== false) {
        echo 0;
    }
    else {
        echo 2;
    }
?>
