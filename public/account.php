<?php

    require("../includes/config.php");

    $rval = 5;

    if ($_POST["pw"] === NULL) {
        // password is empty
        $rval = 1;
    }
    else if ($_POST["conf"] === NULL) {
        // confirmation is empty
        $rval = 2;
    }
    else if ($_POST["pw"] !== $_POST["conf"]) {
        // confirmation does not match password
        $rval = 3;
    }
    else {
        // update user's password
        $updatePw = query("UPDATE users SET hash = ? WHERE id = ?"
                        , crypt($_POST["pw"]), $_SESSION["id"]);
        
        if ($updatePw !== false) {
            $rval = 0;
        } else {
            $rval = 4;
        }
    }

    // build array for ajax response
    $response = [
        "rval" => $rval
    ];

    // spit out content as json
    header("Content-type: application/json");
    echo json_encode($response);

?>
