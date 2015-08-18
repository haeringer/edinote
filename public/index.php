<?php

    /* controller for main */

    require("../includes/config.php");

    $user = query("SELECT username, cash FROM users WHERE id = ?", $_SESSION["id"]);
    $rows = query("SELECT symbol, shares FROM stocks WHERE id = ?", $_SESSION["id"]);


    $usrdir = "../data";
    // scan user directory for files and use array_diff to remove the dots
    $files = array_diff(scandir($usrdir), array('..', '.'));

    // var_dump($files);








    // get string (value of 'cash') out of multidimensional array 'user'
    // (number_format didn't display trailing zeros in decimal places)
    $cash = sprintf("%0.2f", $user[0]["cash"]);

    $positions = [];
    foreach ($rows as $row)
    {
        $stock = lookup($row["symbol"]);

        if ($stock !== false)
        {
            $positions[] = [
                "name" => $stock["name"],
                "price" => $stock["price"],
                "shares" => $row["shares"],
                "symbol" => $row["symbol"],
            ];
        }
    }

    render("main.php", ["positions" => $positions, "cash" => $cash, "files" => $files, "usrdir" => $usrdir, "title" => "Main"]);
    // require("../templates/temp.php");
?>
