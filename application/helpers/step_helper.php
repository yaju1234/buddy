<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function randomKey($length) {
	$key = '';
    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
}
function send_mail($to,$subject,$message){

	ini_set( 'display_errors', 1 );

	error_reporting( E_ALL );

	$from = "tuptup.nath@gmail.com";

	$to = $to;

	$subject = $subject;

	$message = $message;

	$headers = "From:" . $from."\r\n";

	//$headers .='X-Mailer: PHP/' . phpversion();

	$headers .= "MIME-Version: 1.0\r\n";

	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";   

	$headers .= "X-Priority: 3\r\n";
    
    $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

	if(mail($to,$subject,$message, $headers)){

		return "1";
	}else{
		return "0";
	}

}

