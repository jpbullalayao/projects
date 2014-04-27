<?php

    /* REAL WEBSITE FORUM DATABASE */
    $dbhost = 'HyakkisForum.db.10386188.hostedresource.com';
    $dbname = 'HyakkisForum';
    $dbuser = 'HyakkisForum';
    $dbpass = 'Stevenyang@26';
    
    /*$dbhost = 'temphyakkiscorne.db.10386188.hostedresource.com';
    $dbname = 'temphyakkiscorne';
    $dbuser = 'temphyakkiscorne';
    $dbpass = 'Jourdanb1421!';*/
    
    /*$dbhost = 'localhost';
    $dbname = 'hyakkis_corner';
    $dbuser = 'root';
    $dbpass = 'divine187';*/
    
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if (mysqli_connect_errno()) {
        die("Database connection failed: " .
        mysqli_connect_error() .
        " (" . mysqli_connect_errno() . ")"
        );
    } 
?>