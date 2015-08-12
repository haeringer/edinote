<?php

    /* controller for deposits */

    require("../includes/config.php");

    // render deposit form page
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("deposit_form.php", ["title" => "Make Deposit"]);
    }

    // deposit form button was pressed
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate input
        if ($_POST["amount"] == NULL)
        {
            apologize("Please enter an amount!");
        }

        if (preg_match("/^[1-9][0-9]*$/", $_POST["amount"]) == false )
        {
            apologize("Please enter a valid amount of money (no cents allowed)!");
        }

        // add amount to user's cash
        query("UPDATE users SET cash = cash + ? WHERE id = ?", $_POST["amount"], $_SESSION["id"]);

        redirect("index.php");
    }

?>
