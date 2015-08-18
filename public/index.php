<?php

    /* controller for portfolio */

    require("../includes/config.php");

    $rows = query("SELECT symbol, shares FROM stocks WHERE id = ?", $_SESSION["id"]);
    $user = query("SELECT username, cash FROM users WHERE id = ?", $_SESSION["id"]);

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

    render("main.php", ["positions" => $positions, "cash" => $cash, "title" => "Main"]);
    // require("../templates/temp.php");
?>
