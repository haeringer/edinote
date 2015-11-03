<?php

    require("../includes/config.php");

    $rval = NULL;
    $init = $_POST["init"];

    $viewmode = query("SELECT viewmode FROM users WHERE id = ?"
                        , $_SESSION["id"])[0]['viewmode'];

    if ($viewmode === false)
    {
        http_response_code(503);
        exit;
    }

    // switch mode if mode.php was called from switch button
    if ($init === 'false') {
        if ($viewmode === 'false') {

            if (query("UPDATE users SET viewmode = 'true'
                WHERE id = ?", $_SESSION["id"]) !== false) {
                $viewmode = 'true';
            }
            else {
                $rval = 1;
            }
        }
        else {
            if (query("UPDATE users SET viewmode = 'false'
                WHERE id = ?", $_SESSION["id"]) !== false) {
                $viewmode = 'false';
            }
            else {
                $rval = 1;
            }
        }
    } else {
        if ($_SESSION['demo'] === 'true') {
            if (query("UPDATE users SET viewmode = 'false'
                WHERE id = ?", $_SESSION["id"]) !== false) {
                $viewmode = 'false';
            }
            else {
                $rval = 1;
            }
        }
    }

    // json response
    $response = [
        "rval" => $rval,
        "viewmode_r" => $viewmode
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
