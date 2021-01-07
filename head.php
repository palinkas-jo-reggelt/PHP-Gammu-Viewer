<?php
	include_once("config.php");

	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		if (!($_COOKIE['password'] === md5($logins[$_COOKIE['username']]))) {
			header('Location: login.php');
		}
	} else {
		header('Location: login.php');
	}
?>

<!DOCTYPE html> 
<html>
<head>
<title>SMS Gateway</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" media="all" href="stylesheet.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"> 
</head>
<body>

<div class="header">
	<div class="banner">
		<a href="./">
		<div class="banner-left">
			<div class="banner-left-left"><h1>S</h1></div>
			<div class="banner-left-right"><h1>M</h1></div>
			<div class="clear"></div>
		</div>
		<div class="banner-right"><h1>S</h1>
		</div>	
		<div class="clear"></div>
		</a>
	</div>
</div>

<div class="wrapper">
