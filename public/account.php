<?php

    /* controller for user account page where he can change his password */

    // configuration
    require("../includes/config.php");

    // initialize variable for showing message on page or not
    $pw_success = false;

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("account_form.php", ["title" => "Your Account", "pw_success" => $pw_success]);
    }

    // change password button was pressed
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // display error messages in case of wrong information by registrant
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

        // update user's password
        query("UPDATE users SET hash = ? WHERE id = ?",
        crypt($_POST["password"]), $_SESSION["id"]);

        $pw_success = true;

        render("account_form.php", ["title" => "Your Account", "pw_success" => $pw_success]);
    }

?>
