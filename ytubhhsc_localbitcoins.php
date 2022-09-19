<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";

///////////////////////////////
$datestamp = date("Y-m-d-g-i-a");      // Current date to append to filename of backup file in format of YYYY-MM-DD
 
/* CONFIGURE THE FOLLOWING SEVEN VARIABLES TO MATCH YOUR SETUP */
$dbuser = "pixdeposit";            // Database username
$dbpwd = "'SA{oc&%0Q[^M'";            // Database password
$dbname = "vikkin__3107";            // Database name. Use --all-databases if you have more than one
$filename= "backup-$datestamp.sql.gz";   // The name (and optionally path) of the dump file
$host="pix.cduuflmnmg2e.sa-east-1.rds.amazonaws.com";



$command = "mysqldump -h $host -u $dbuser --password=$dbpwd $dbname | gzip > $filename";


echo $command;

$result = passthru($command);
 
 //////////////////////


//PHPMailer Object
$mail = new PHPMailer(true); //Argument true in constructor enables exceptions

//From email address and name
$mail->From = "ytubhhsc@alphakoin.com";
$mail->FromName = "Backup Email";

//To address and name
$mail->addAddress("globocoins.trade@gmail.com", "backup email ".$filename);
// $mail->addAddress("ytubhhsc@alphakoin.com"); //Recipient name is optional

//Address to which recipient will reply
$mail->addReplyTo("susheel3010@gmail.com", "Reply");

//CC and BCC
$mail->addCC("susheel3010@gmail.com");
$mail->addBCC("sakshk2019@gmail.com");


//Provide file path and name of the attachments
$mail->addAttachment($filename);        

//$mail->addAttachment( "walletapiv3.tar.gz");    



// $mail->addAttachment("images/profile.png"); //Filename is optional

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Backup Email";
$mail->Body = "<i>Backup Email</i>";
$mail->AltBody = "Backup Email";

try {
    $mail->send();
    echo "Message has been sent successfully";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
