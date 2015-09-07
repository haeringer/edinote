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

    $tagged = false;
    // $tagNum = 'init' . $_POST["tag"];

    // function tagQuery(i) {
    //     for ($i = ; $i < sizeof($tags[0]); $i++) {
    //
    //         $tagNum = 'tag' . $i;
    //         echo var_dump($tagNum);
    //
    //         if ($tags[0][$tagNum] === NULL) {
    //             $result = query("UPDATE files SET tag1 = ? WHERE id = ? AND file = ?",
    //                         $_POST["tag"], $_SESSION["id"], $filename);
    //             return $result;
    //         }
    //         else {
    //             echo 'tag1 is not empty';
    //             tagQuery($i);
    //         }
    //     }
    // }

    // TODO rewrite query to function or for-loop


    // for ($i = 1; $i <= 3; $i++) {
    //     $tagNum = 'tag' . $i;
    //     if ($tags[0][$tagNum] === NULL) {
    //         $tagged = query("UPDATE files SET ? = ? WHERE id = ? AND file = ?",
    //                     $tagNum, $_POST["tag"], $_SESSION["id"], $filename);
    //     }
    //     else {
    //         echo 3;
    //         exit;
    //     }
    // }


    if ($tags[0]['tag1'] === NULL) {
        $tagged = query("UPDATE files SET tag1 = ? WHERE id = ? AND file = ?",
                    $_POST["tag"], $_SESSION["id"], $filename);
    }
    else if ($tags[0]['tag2'] === NULL) {
        $tagged = query("UPDATE files SET tag2 = ? WHERE id = ? AND file = ?",
                    $_POST["tag"], $_SESSION["id"], $filename);
    }
    else if ($tags[0]['tag3'] === NULL) {
        $tagged = query("UPDATE files SET tag3 = ? WHERE id = ? AND file = ?",
                    $_POST["tag"], $_SESSION["id"], $filename);
    }
    else {
        echo 3;
        exit;
    }


    if ($tagged !== false) {
        echo 0;
    }
    else {
        echo 2;
    }

?>
