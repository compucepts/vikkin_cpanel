<?php

 
 

///////////////////////////////
$datestamp = date("Y-m-d-g-i-a");      // Current date to append to filename of backup file in format of YYYY-MM-DD
 
/* CONFIGURE THE FOLLOWING SEVEN VARIABLES TO MATCH YOUR SETUP */
$dbuser = "root";            // Datvikkin__3107  abase username
$dbpwd = "mWPJ7+Qb-PLP";            // Database password
$dbname = "vikkin__3107";            // Database name. Use --all-databases if you have more than one
$filename= "backup/backup-$datestamp.sql.gz";   // The name (and optionally path) of the dump file
$host="localhost";



$command = "mysqldump -h $host -u $dbuser --password=$dbpwd $dbname | gzip > $filename";


echo $command;

$result = passthru($command);
 
 //////////////////////


