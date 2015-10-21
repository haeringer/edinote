<?php

    /**
     * Edinote constants
     *
     * Ben Haeringer
     * ben.haeringer@gmail.com
     *
     */

    // database type: mysql or sqlite (mysql not fully implemented yet!)
    define("DBTYPE", "sqlite");

    // database name
    define("DATABASE", "edinote");

    // only needed for mysql: database server
    define("SERVER", "localhost");

    // only needed for mysql: database username
    define("USERNAME", "edinote");

    // only needed for mysql: database password
    define("PASSWORD", "secret");

    // user data directory (for production use "/var/lib/edinote/data/")
    define("DATADIR", "../data/");

?>
