<?php
/* Database connection start */
$servername = "localhost";
$username = "gammu";
$password = "supersecretpassword";
$dbname = "gammu";
$con = mysqli_connect($servername, $username, $password, $dbname);
if (mysqli_connect_errno()) {
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
}

$pdo = new PDO('mysql:host='.$servername.';dbname='.$dbname.'', ''.$username.'', ''.$password.'');
?>