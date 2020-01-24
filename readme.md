## PHP Gammu Frontend

Just a simple PHP frontend for Gammu SMS that allows you to view outbox & inbox, search messages and send messages. The form for sending messages can send long messages (up to 999 char) and can be sent at a future date of your choosing.

Nothing fancy here - no Restful API, no contact database, no mass-SMS. This frontend uses the unaltered Gammu database only. Other Gammu functions should be scripted using gammu-smsd-inject.


## Prerequisites

* Working Gammu
* Webserver with PHP
* MySQL database for Gammu


## Instructions

* Drop files into web server folder
* Edit MySQL variables in cred.php
* Edit .htaccess to allow your LAN subnet (or delete if you don't want any security)
* (Optional) Edit inbox.php and outbox.php names/numbers to display names instead of mobile numbers for frequent contacts