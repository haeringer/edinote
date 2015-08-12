<?php

    /* controller for buy stocks page */

    require("../includes/config.php");

    // render page with buy form
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("buy_form.php", ["title" => "Buy shares"]);
    }

    // user pressed buy button
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // input validation
        if ($_POST["symbol"] == NULL || $_POST["shares"]  == NULL)
        {
            apologize("Please enter stock symbol and number of shares!");
        }

        if (preg_match("/^\d+$/", $_POST["shares"]) == false)
        {
            apologize("You can buy only whole shares of stocks!");
        }

        $stock = lookup(strtoupper($_POST["symbol"]));

        if ($stock === false)
        {
            apologize("Your stock symbol was invalid!");
        }

        $price = $stock["price"] * $_POST["shares"];

        // check if user can afford shares
        $cash_arr = query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
        $cash = floatval($cash_arr[0]["cash"]);

        if ($price > $cash)
        {
            apologize("You don't have enough cash!");
        }

        query("INSERT INTO stocks (id, symbol, shares) VALUES(?,?,?) ON
        DUPLICATE KEY UPDATE shares = shares + VALUES(shares)",
        $_SESSION["id"], $stock["symbol"], $_POST["shares"]);

        // subtract value of sold stocks from user's cash
        query("UPDATE users SET cash = cash - ? WHERE id = ?", $price, $_SESSION["id"]);

        // log transaction
        query("INSERT INTO history (id, transaction, symbol, shares, price)
        VALUES (?, 'BUY', ?, ?, ?);",
        $_SESSION["id"], $stock["symbol"], $_POST["shares"], $stock["price"]);

        redirect("index.php");
    }

?>
