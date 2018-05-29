<?php
if ( ! defined('BASEPATH') )
  exit( 'No direct script access allowed' );
define("GOOGLE_API_KEY", "AIzaSyBqpy194NBTI07zsq1DdJHJQrrOkx9QNEc"); 
class Fcm
{
    public function __construct() {
        
    }

    function send_fcm_notification($fields){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = json_encode ( $fields );
        $headers = array (
            'Authorization: key='.getenv('FIREBASE_AUTH_KEY'),
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

        $result = curl_exec ( $ch );

        curl_close ( $ch );
    }

   /********************** GCM PUSH NOTIFICATION START *********************/
	
	 function send_notification($registatoin_ids, $message) {
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message, 
        );
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
          $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }       
        curl_close($ch);
    }
   /********************** GCM PUSH NOTIFICATION END  *********************/
}
?>
