<?php

    /* controller for selling stocks */

    require("../includes/config.php");

    // retrieve stock portfolio from user
    $portfolio = query("SELECT * FROM stocks WHERE id = ?", $_SESSION["id"]);

    /* extract symbols of multidimensional portfolio array for usage in
    * drop down and for cash calculation */
    $stocks = [];
    foreach ($portfolio as $row)
    {
        $stocks[] = ["symbol" => $row["symbol"]];
    }

    // render sell form page
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("sell_form.php", ["title" => "Sell Stocks", "stocks" => $stocks]);
    }

    // user pressed sell button
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate symbol choice
        if ($_POST["symbol"] == NULL)
        {
            apologize("Please choose a stock!");
        }

        // get number of shares to be sold
        $shares = query("SELECT shares FROM stocks WHERE id = ? and symbol = ?",
        $_SESSION["id"], $_POST["symbol"]);

        // sell stock by deleting it from database
        query("DELETE FROM stocks WHERE id = ? AND symbol = ?",
        $_SESSION["id"], $_POST["symbol"]);

        // calculate value of sold stocks
        $tosell = lookup($_POST["symbol"]);
        $value = $tosell["price"] * $shares[0]["shares"];

        // add value of sold stocks to user's cash
        query("UPDATE users SET cash = cash + ? WHERE id = ?",
        $value, $_SESSION["id"]);

        // log transaction
        query("INSERT INTO history (id, transaction, symbol, shares, price)
        VALUES (?, 'SELL', ?, ?, ?);",
        $_SESSION["id"], $_POST["symbol"], $shares[0]["shares"], $tosell["price"]);

        redirect("index.php");
    }

?>
