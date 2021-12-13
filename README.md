## PHP Gammu Frontend

Just a simple PHP frontend for Gammu SMS that allows you to view outbox & inbox, search messages and send messages. 

Nothing fancy here - no Restful API, no contact database, no mass-SMS. This frontend uses the unaltered Gammu database only. Other Gammu functions should be scripted using gammu-smsd-inject. Frequent contact names (instead of numbers) can be displayed by editing name variables in config.php.

I just wanted a simple viewer to see messages generated programmatically by other scripts. That's all this is, with a couple of extra very minor features.


## Features

* Uses unaltered Gammu database (no new tables/columns/etc)
* Send short/long messages (up to 999 char) immediately or at future time 
* Long messages are properly sequenced to arrive as a single long message
* Displays outbox (including long message outbox in sequence)
* Displays inbox/sent items
* Displays connection status: CONNECTED or DISCONNECTED (StatusFrequency in SMSDRC must be enabled and < 90)
* Search inbox/sent items by keyword or phone number
* Displays names instead of numbers if configured (from PHP array - no database entries)
* Mobile browser support (turns wide tables into cards)

## Prerequisites

* Working Gammu
* Webserver with PHP
* MySQL database for Gammu


## Instructions

* Drop files into web server folder
* Rename config.php.dist to config.php and edit variables
* Edit .htaccess to allow your LAN subnet (or delete if you don't want any security)
