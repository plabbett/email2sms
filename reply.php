<?php

	require_once 'vendor/autoload.php';
	require_once 'config.php';
	
	//inbound texts (i.e., replies) will come here
	//handle them however you see fit for your business logic
	
	//for example, email replies to you:
	//mail('you@example.com', 'Inbound SMS', print_r($_REQUEST, true));
	
	
	//or, if you log the message when you send it (including who you sent it to)
	//you could look it up here, and perform a specific action
	
	//this just writes a blank <Response></Response> xml document for us
	$twiml = \Services_Twilio_Twiml;
	die($twiml);