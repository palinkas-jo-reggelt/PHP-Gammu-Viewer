<?php

	if ($Database['driver'] == 'mysql') {
		$pdo = new PDO("mysql:host=".$Database['host'].";port=".$Database['port'].";dbname=".$Database['dbname'], $Database['username'], $Database['password']);
	} elseIf ($Database['driver'] == 'odbc') {
		$pdo = new PDO("odbc:Driver={".$Database['dsn']."};Server=".$Database['host'].";Port=".$Database['port'].";Database=".$Database['dbname'].";User=".$Database['username'].";Password=".$Database['password'].";");
	} else {
		echo "Configuration Error - No database driver specified";
	}

	function repairNumber($number) {
		$number = str_replace(' ', '', $number);
		return str_replace($ServerLocation['CountryCode'], '', $number);
	}

	function secure($string) {
		return trim(htmlspecialchars(addslashes($string)));
	}

	function onlyLetters($string, $strong = false) {
		if ($strong){
			return preg_replace('/[^\p{L}\p{N} ]+/', '', $string);
			return preg_replace('/[^\p{L}\p{N} \'.-]+/', '', $string);
		}
	}

	function displayMobileNumber($mobilenumber){
		if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $mobilenumber,  $matches )){
			$phone = '('.$matches[1].') '.$matches[2].'-'.$matches[3];
			$result = $phone;
		} else if ($mobilenumber == 0) {
			$result = "&nbsp;";
		} else {
			$result = $mobilenumber;
		}
		return $result;
	}

?>
