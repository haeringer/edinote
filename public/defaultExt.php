<?php

    require(__DIR__ . "/../includes/config.php");

    $rval = NULL;
    $init = $_POST["init"];

    $defaultExt = query("SELECT defaultext FROM users WHERE id = ?"
                        , $_SESSION["id"])[0]['defaultext'];

    if ($defaultExt === false)
    {
        http_response_code(503);
        exit;
    }

    // switch mode if defaultExt.php was called from account settings
    if ($init === 'false') {

        if (query("UPDATE users SET defaultext = ? WHERE id = ?"
            , $_POST["extDefault"], $_SESSION["id"]) !== false) {
            $rval = 0;
        }
        else {
            $rval = 1;
        }
    }
    else {
        // if initial page load && demo user, always set to 'md'
        if ($_SESSION['demo'] === 'true') {
            if (query("UPDATE users SET defaultext = 'md' WHERE id = ?"
            , $_SESSION["id"]) !== false) {
                $defaultExt = 'md';
                $rval = 0;
            }
            else {
                $rval = 1;
            }
        }
    }

    // json response
    $response = [
        "rval" => $rval,
        "demo" => $_SESSION["demo"],
        "ext" => $defaultExt
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
