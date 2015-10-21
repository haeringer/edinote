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

        $changeExt = query("UPDATE users SET defaultext = ? WHERE id = ?"
                            , $_POST["extDefault"], $_SESSION["id"]);

        if ($changeExt !== false) {
            $rval = 0;
        }
        else {
            $rval = 1;
        }
    }
    
    // json response
    $response = [
        "rval" => $rval,
        "ext" => $defaultExt
    ];

    header("Content-type: application/json");
    echo json_encode($response);

?>
