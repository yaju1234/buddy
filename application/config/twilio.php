<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Mode ("sandbox" or "prod")
	 **/
	$config['mode']   = 'sandbox';

	/**
	 * Account SID
	 **/
	$config['account_sid']   = getenv('ACCOUNT_SID');

	/**
	 * Auth Token
	 **/
	$config['auth_token']    = getenv('AUTH_TOKEN');

	/**
	 * API Version
	 **/
	$config['api_version']   = getenv('TWILIO_API_VERSION');

	/**
	 * Twilio Phone Number
	 **/
	$config['number']        = getenv('TWILIO_FROM_NUMBER');


/* End of file twilio.php */