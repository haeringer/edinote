<?php

    /* controller for history */

    require("../includes/config.php");

    $rows = query("SELECT transaction, symbol, shares, price, datetime FROM history WHERE id = ?", $_SESSION["id"]);

    $positions = [];
    foreach ($rows as $row)
    {
        $positions[] = [
            "transaction" => $row["transaction"],
            "price" => $row["price"],
            "shares" => $row["shares"],
            "symbol" => $row["symbol"],
            "datetime" => $row["datetime"]
        ];
    }

    render("history.php", ["positions" => $positions, "title" => "Transaction History"]);

?>
