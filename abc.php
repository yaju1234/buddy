<?php



ini_set( 'display_errors', 1 );

error_reporting( E_ALL );

$from = "tuptup.nath@gmail.com";

$to = "tuptup.nath@gmail.com";

$subject = "PHP Mail Test script";

$message = "This is a test to check the PHP Mail functionality";

$headers = "From:" . $from."\r\n";

$headers .='X-Mailer: PHP/' . phpversion();

$headers .= "MIME-Version: 1.0\r\n";

$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";   

if(mail($to,$subject,$message, $headers)){

echo "Test email sent";
}
