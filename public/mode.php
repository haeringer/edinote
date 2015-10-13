<?php

    require(__DIR__ . "/../includes/config.php");
    
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
            $changemode = query("UPDATE users SET viewmode = 'true' 
                                WHERE id = ?", $_SESSION["id"]);

            if ($changemode !== false) {
                $viewmode = 'true';
            }
            else {
                $rval = 1;
            }
        }
        else {
            $changemode = query("UPDATE users SET viewmode = 'false' 
                                WHERE id = ?", $_SESSION["id"]);

            if ($changemode !== false) {
                $viewmode = 'false';
            }
            else {
                $rval = 1;
            }
        }
    }
    // if mode.php was called from initial page load, just spit out current mode
    // else if ($init === 'true') {
    //     $viewmode = $current_mode;
    // }

    else {
        $rval = 2;
    }
    
    // json response
    $response = [
        "rval" => $rval,
        "viewmode_r" => $viewmode
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
