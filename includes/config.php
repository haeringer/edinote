<?php

    /**
     * Edinote config.php - based on CS50 pset7
     *
     * Ben Haeringer
     * ben.haeringer@gmail.com
     *
     * Configures pages.
     */

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // requirements
    require("constants.php");
    require("functions.php");

    // enable sessions
    session_start();

    // require authentication for all pages except /login.php and /logout.php
    if (!in_array($_SERVER["PHP_SELF"], ["/login.php", "/logout.php"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("login.php");
        }
    }

?>
