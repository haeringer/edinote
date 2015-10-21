<?php

    /* controller for registration page */

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // display error messages in case of wrong information by registrant
        if ($_POST["username"] == NULL)
        {
            apologize("Username is empty!");
        }
        if ($_POST["password"] == NULL)
        {
            apologize("Password is empty!");
        }
        if ($_POST["confirmation"] == NULL)
        {
            apologize("Please confirm your password!");
        }
        if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("Confirmation does not match password!");
        }

        // if insert user into database
        $result = query("INSERT INTO users (username, hash, cash)
            VALUES(?, ?, 10000.00)",
            $_POST["username"],
            crypt($_POST["password"]));

        if ($result === false)
        {
            apologize("Username already exists!");
        }

        // if registration succeeded, locate the user id and remember it in
        // the session cookie, then redirect user to index.php
        else
        {
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];

            $_SESSION["id"] = $id;

            redirect("/");
        }
    }

?>
