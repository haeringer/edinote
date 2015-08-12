<?php

    /* controller for Get Quote */

    require("../includes/config.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("quote_form.php", ["title" => "Get Quote"]);
    }

    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ($_POST["symbol"] == NULL)
        {
            apologize("Please enter a stock symbol!");
        }

        $stock = lookup(strtoupper($_POST["symbol"]));

        if ($stock === false)
        {
            apologize("Your stock symbol was invalid!");
        }
        else
        {
            render("quote.php", ["title" => "Quote for " . $stock["symbol"],
                "symbol" => $stock["symbol"],
                "name" => $stock["name"],
                "price" => $stock["price"]
                ]);
        }
    }

?>
