<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("login_form.php", ["title" => "Log In"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $rval = NULL;

        // validate submission
        if (empty($_POST["username"]))
        {
            $rval = 2;
        }
        else if (empty($_POST["password"]))
        {
            $rval = 3;
        }
        else {
            // query database for user
            $rows = query("SELECT * FROM users WHERE username = ?"
                            , $_POST["username"]);

            // if we found user, check password
            if (count($rows) == 1)
            {
                // first (and only) row
                $row = $rows[0];

                // compare hash of user's input against hash in database
                if (crypt($_POST["password"], $row["hash"]) == $row["hash"])
                {
                    // store user's ID in session
                    $_SESSION["id"] = $row["id"];

                    $rval = 0;
                } else {
                    // credentials not valid
                    $rval = 1;
                }
            } else {
                $rval = 1;
            }
        }

            // build array for ajax response
        $response = [
            "rval" => $rval
        ];

        // spit out content as json
        header("Content-type: application/json");
        echo json_encode($response);
    }

?>
