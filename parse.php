<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

$raw_message = fopen('php://stdin', 'r');
$email_content = "";
while (!feof($raw_message)) {
    $email_content .= fread($raw_message, 1024);
}
fclose($raw_message);

$parser = new \PhpMimeMailParser\Parser();
$parser->setText($email_content);

$parsed_email = array(
    'to' => htmlspecialchars($parser->getHeader('to')),
    'delivered_to' => $parser->getHeader('delivered-to'),
    'from' => htmlspecialchars($parser->getHeader('from')),
    'subject' => $parser->getHeader('subject'),
    'text' => $parser->getMessageBody('text'),
    'html' => $parser->getMessageBody('html'),
    //'attachments' => $parser->getAttachments()

);

// the above code will give you something like:
// John Smith <john@smith.com>
// so to get the email address we need to use explode() function:

function get_email_address($input){
    $input = explode('&lt;', $input);
    $output = str_replace('&gt;', '', $input);
    $name = $output[0]; // THE NAME
    $email =  preg_replace('/[<>]/', '', $output[1]);
 //   $email = $output[1]; // THE EMAIL ADDRESS
    return $email;
}

$from = get_email_address($parsed_email['from']); // NOW THIS IS THE EMAIL
$to = get_email_address($parsed_email['to']); // NOW THIS IS THE EMAIL

if (preg_match('/(.+)(@.+)$/', $to, $nmatches)) {
    $user = $nmatches[1]; //does postfix always have <> in to field?
    $domain = $nmatches[2];
}

if ($domain = $config['allowed_domain']) {

    if (strlen($user) == 11) { //sender is 1 + ten digits


//$users array loaded in config contains sending email twillo sms number pairs

        foreach ($users as $numberfrom => $emailfrom) {  //loop through each pair

            if ($emailfrom == $from) {  //found matching email as valid sender email pair

                try {
                    $sms_client = new \Services_Twilio($config['twilio_sid'], $config['twilio_token']);
                    $sms_client->account->messages->sendMessage($numberfrom, "+.$user", $parsed_email['text']);
                } catch (\Services_Twilio_RestException $e) {
                    //unable to send via twilio,
                    die();
                } catch (Exception $e) {
                    //unable to send via twilio,
                    die();

                }
                //message was sent. log how you see fit.
                die();
            }
        }
        mail($config['admin_email'], 'Inbound Email From unknown sender', print_r($from, true));
    } else {
        //not 11 digits
        die();
    }
} else {
    //not sender and domain
    die();
}


