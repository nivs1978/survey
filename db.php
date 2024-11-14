<?php
    $dbname = 'xxxx_dkdb';
    $dbuser = 'xxxx_dkdb';
    $dbpass = 'yyyyyyyyyy';
    $dbhost = 'xxxx.dk.mysql';
    $dbhandle = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Unable to connect to '$dbhost'");
    $selected = mysqli_select_db($dbhandle, $dbname) or die("Could not open the database '$dbname'");
?>