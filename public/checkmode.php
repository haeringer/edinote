<?php

    require(__DIR__ . "/../includes/config.php");

    $mode = query("SELECT mode FROM users WHERE id = ?", $_SESSION["id"])[0]['mode'];

    if ($mode === false)
    {
        http_response_code(503);
        exit;
    }

    echo $mode;

?>
