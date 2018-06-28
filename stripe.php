<?php

require_once('stripe-php-6.9.0/init.php');


  \Stripe\Stripe::setApiKey("sk_test_GOeS0HxRJZVM1Z77uLYEN7xn");
  
  //$token = 'tok_1Ci1WIBaXTdpZtAaZ6S7zdQY'; /*$_POST['stripeToken']*/;
  $token =  $_POST['stripeToken'];
  $price =  $_POST['price'];
  $description = 'Order from username';
  
  try {
    $charge = \Stripe\Charge::create(array(
      "amount" => $price, // amount in cents
      "currency" => "usd",
      "source" => $token,
      "description" => $description
      ));
    $chargeID = $charge['id'];
    echo '{"status":true}'
   // print("\n" . 'Successfuly created charge with ID: <a target="_blank" href="https://dashboard.stripe.com/test/payments/' . $chargeID . '">' . $chargeID . '</a>' . "\n");
  } catch(\Stripe\Error\Card $e) {
    // Since it's a decline, \Stripe\Error\Card will be caught
    $body = $e->getJsonBody();
    $err  = $body['error'];
   /* print('Status is:' . $e->getHttpStatus() . "\n");
    print('Type is:' . $err['type'] . "\n");
    print('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    print('Param is:' . $err['param'] . "\n");
    print('Message is:' . $err['message'] . "\n");*/
    echo '{"status":false}'
  } catch (\Stripe\Error\RateLimit $e) {
    echo '{"status":false}'
    // Too many requests made to the API too quickly
  } catch (\Stripe\Error\InvalidRequest $e) {
    echo '{"status":false}'
    // Invalid parameters were supplied to Stripe's API
  } catch (\Stripe\Error\Authentication $e) {
   echo '{"status":false}'
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
  } catch (\Stripe\Error\ApiConnection $e) {
   echo '{"status":false}'
    // Network communication with Stripe failed
  } catch (\Stripe\Error\Base $e) {
    echo '{"status":false}'
    // Display a very generic error to the user, and maybe send
    // yourself an email
  } catch (Exception $e) {
    echo '{"status":false}'
    // Something else happened, completely unrelated to Stripe
  }
//}
?>