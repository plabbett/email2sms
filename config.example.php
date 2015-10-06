<?php

$config['allowed_domain'] = '@whereyousendyoursmsemails.net';
$config['twilio_sid'] = 'Account SID';
$config['twilio_token'] = 'Twillo Secret Token';
$config['admin_email'] = 'root@foo.com';

//from number, optionally enable Scaler (new feature that provides a bank of numbers + sticky numbers) via messaging copilot

$users = [

    "+12345678901"=> "john@madeupdomain1.com",
    "+198765432109"=> "jane@madeupdomain2.com",
    "+154321098765"=> "juan@madeupdomain3.com"
];
