<?php

    require(__DIR__ . "/../includes/config.php");

    $init = $_POST["init"];
    // var_dump($init);
    $current_mode = query("SELECT mode FROM users WHERE id = ?", $_SESSION["id"])[0]['mode'];

    if ($current_mode === false)
    {
        http_response_code(503);
        exit;
    }

    // switch mode if mode.php was called from switch button
    if ($init === 'false') {
        if ($current_mode === 'edit') {
            $changemode = query("UPDATE users SET mode = 'view' WHERE id = ?", $_SESSION["id"]);

            if ($changemode !== false) {
                echo 'view';
            }
        }
        else if ($current_mode === 'view') {
            $changemode = query("UPDATE users SET mode = 'edit' WHERE id = ?", $_SESSION["id"]);

            if ($changemode !== false) {
                echo 'edit';
            }
        }
    }
    // if mode.php was called from initial page load, just spit out current mode
    else if ($init === 'true') {
        echo $current_mode;
    }

    else {
        echo 1;
    }

?>
