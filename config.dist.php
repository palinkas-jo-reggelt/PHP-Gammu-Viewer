<?php

/*	Site Logon Variables 
	Username and password to allow access to site
*/
$logins = array(
	'admin'       => 'supersecretpassword',
	'userdude'    => 'othersecretpassword',
	'otherdude'   => 'anotherpassword'
);

/*  Cookie Duration in days  */
$cookie_duration = 90; 

/*	Database Variables (MySQL databases only)

	'driver' = connection type
	
		For MySQL use driver = 'mysql'
		For ODBC  use driver = 'odbc'
		
		* When opting for ODBC use correct DSN! *
		* Example: "MariaDB ODBC 3.0 Driver"    *
		* Exact spelling is critical!           *
*/
$Database = array (
	'host'        => 'localhost',
	'username'    => 'gammu',
	'password'    => 'supersecretpassword',
	'dbname'      => 'gammu',
	'port'        => '3306',
	'driver'      => 'mysql',
	'dsn'         => 'MariaDB ODBC 3.1 Driver'
);

/*  Location Variables */
$ServerLocation = array (
	'CountryCode' => '+1' // telephone country code prefix
);

/*  TimeZone required to determine if gammu is connected or not - status.php
    TimeZone offset required to compare last update in database against current time
    https://www.php.net/manual/en/timezones.php
*/
$TimeZone = 'America/New_York';


/*  Contact Variables */
/*  Optional - change if you want to use real names/numbers - leaving the examples will not harm anything */
$Contacts = array (
	'2125551212'  => 'Jim', 
	'5615559876'  => 'Bill',
	'4045555463'  => 'Jean',
	'1235558888'  => 'Sandy', 
	'9999999999'  => 'Frank',
	'' => ''
);


/*  Custom URL shortner service - converts to http link */
/*  Enable this if you receive shortlinks without http(s):// prefix  */
$UseShortURL      = False;             // Only use this if your short link service does NOT prefix URLs in text messages with http://
$ShortURLDomain   = 'url.domain.tld';  // URL your short link service uses for short links (not the name of the service) - works great with yourls
$ShortURLSSL      = True;              // True prefixes https://, False prefixes http://


/*  MESSAGE HISTORY: Number of records per page */
$no_of_records_per_page = 20;

?>
