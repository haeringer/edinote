<?php

    require(__DIR__ . "/../includes/config.php");

    $current_mode = query("SELECT mode FROM users WHERE id = ?", $_SESSION["id"])[0]['mode'];

    if ($current_mode === false)
    {
        http_response_code(503);
        exit;
    }

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

    else {
        echo 1;
    }

?>
