== Overview ==

The setup listed below assumes you are on CentOS and clone this repo into /opt/email2sms

Change your paths to match if you don't use the same setup.

Additionally, the reply.php will need to be in a web-enabled location that Twilio can hit.
However, you should not expose the entire repo to the world.


```git clone https://github.com/plabbett/email2sms```
```cd email2sms```
```composer install```

Rename config.example.php to config.php
Modify as you see fit.


== Configure Sendmail Alias and Domain Catch All ==

=== Create alias to our script ===
In sendmail, add an alias to pipe email to the PHP script.

nano /etc/aliases
	
Add: youralias: “|/usr/bin/php –q /opt/email2sms/parse.php”

Save the file

=== Point catch-all to alias ===
nano /etc/mail/virtusertable
	
Add: @yourdomain.com youralias
	
(This hostname should be the host of the server so mail actually gets sent there)


This next configuration tells the sendmail server it can listen for messages for yourdomain.com

nano /etc/mail/local-host-names

Add: yourdomain.com



You can check /var/log/maillog for routing problems on messages. 

Run the command “newaliases”

Restart sendmail




Navigate to parse.php and set 755 permissions:

i.e., chmod 755 /opt/email2sms/parse.php

[root@support smrsh]# cd /opt/email2sms

[root@support email2sms]# chmod 755 parse.php

Now we have to tell sendmail to allow smrsh to use php

[root@support email2sms]# cd /etc/smrsh 

[root@support smrsh]# ln -s /usr/bin/php ./php

[root@support smrsh]# ln -s /opt/email2sms/parse.php ./parse.php


You may need to perform a newaliases again and then service sendmail restart


At this point you can send an email in the following way:

Send email to <10digitphonenumber>@yourdomain.com

Example: Send an email to 5551234567@yourdomain.com

Be sure you align your directory structures with where the scripts actually are. 

Environments can differ. 

