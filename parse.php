<?php

	require_once 'vendor/autoload.php';
	require_once 'config.php';
	
	$raw_message = fopen('php://stdin', 'r');
	$email_content = "";
	while (!feof($raw_message)) {
		$email_content .= fread($raw_message, 1024);
	}
	fclose($raw_message);

	$parser = new \MimeMailParser\Parser();
	$parser->setText($email_content);
	
	$parsed_email = array(
		'to' => $parser->getHeader('to'),
		'delivered_to' => $parser->getHeader('delivered-to'),
		'from' => $parser->getHeader('from'),
		'subject' => $parser->getHeader('subject'),
		'text' => $parser->getMessageBody('text'),
		'html' => $parser->getMessageBody('html'),
		//'attachments' => $parser->getAttachments()
		
	);

	
	if(strpos(strtolower($parsed_email['from']), strtolower($config['allowed_domain'])) !== false){
		//email will be accepted. log as you see fit.
	}
	else{
		//email will not be accepted. this is domain level access control.
		die();
	}

	$mailAddress = preg_match('/(?:<)(.+)(?:>)$/', $parsed_email['to'], $matches);
	$tmp = explode('@', $matches[1]);
	if(count($tmp) == 2){
		if(strlen($tmp[0]) == 10){
			try{
				$sms_client = new \Services_Twilio($config['twilio_sid'], $config['twilio_token']);
				$sms_client->account->messages->sendMessage($config['twilio_from'], "+1${tmp[0]}", $parsed_email['text']);
			}
			catch(\Services_Twilio_RestException $e){
				//unable to send via twilio,
				die();
			}
			catch(Exception $e){
				//unable to send via twilio,
				die();
		
			}
	        
			//message was sent. log how you see fit.
			die();

		}
		else{
			//invalid format for sms number
			die();
		}
	}
	else{
		//we didn't parse a correctly formatting email
		die();
	}
