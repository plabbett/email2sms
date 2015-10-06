<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

//  This example checks account info and loops through Associative Array email number pairs in config


if (isset($_REQUEST['To']) && isset($_REQUEST['From'])&& $_REQUEST['AccountSid'] == $config['twilio_sid']) {
    $to = $_REQUEST['To'];
    $from = $_REQUEST['From'];
    foreach ($users as $number => $emailto) {
        if ($number == $to) {  //found matching number email pair
            $smsfrom = preg_replace('/\+/', '', $from); //remove + as not legal email address char
            $mailsubject = "";
            $mailfrom = $smsfrom . $config['allowed_domain'];
            mail($emailto, $mailsubject, $_REQUEST['Body'], "From: $mailfrom");
            exit;
        }
    }
    // Sending Number does not have matching email to forward to - email to admin
    mail($config['admin_email'], 'Inbound SMS Number has no Email', print_r($_REQUEST, true));

}


//or, if you log the message when you send it (including who you sent it to)
//you could look it up here, and perform a specific action foo

//this just writes a blank <Response></Response> xml document for us
//$twiml = new \Services_Twilio_Twiml;
//die($twiml);


