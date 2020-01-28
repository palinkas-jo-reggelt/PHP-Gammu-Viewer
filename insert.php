<?php include("config.php") ?>
<?php
	$con = mysqli_connect($Database['host'], $Database['username'], $Database['password'], $Database['dbname']);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	$pdo = new PDO('mysql:host='.$Database['host'].';dbname='.$Database['dbname'].'', ''.$Database['username'].'', ''.$Database['password'].'');

	function repairNumber($number) {
		$number = str_replace(' ', '', $number);
		return str_replace($ServerLocation['CountryCode'], '', $number);
	}

	function secure($string) {
        return trim(htmlspecialchars(addslashes($string)));
	}

	function onlyLetters($string, $strong = false) {
		if ($strong)
			return preg_replace('/[^\p{L}\p{N} ]+/', '', $string);
			return preg_replace('/[^\p{L}\p{N} \'.-]+/', '', $string);
	}

	// Escape user inputs for security
	$MobileNumber = mysqli_real_escape_string($con, $_POST['MobileNumber']);
	$MessagePost = mysqli_real_escape_string($con, $_POST['Message']);
	$SendAt = mysqli_real_escape_string($con, $_POST['SendAt']);
	 
	// https://github.com/moulinraphael/gammu/blob/master/sendsms.php
	$num = secure(repairNumber($MobileNumber));
	// $message = utf8_decode(addslashes($MessagePost));
	$message = utf8_decode($MessagePost);
	$messages = str_split($message, 153); //On fait des blocs de 153 caractères pour Gammu
	$message = array_shift($messages);
	$ref = sprintf("%02x", rand(1, 255));
	$nb = sprintf("%02d", 1 + count($messages));

	//Envoi du SMS demandé
	$pdo->exec('INSERT INTO outbox SET '.
		'DestinationNumber = "'.$num.'", '.
		'UDH = "050003'.$ref.$nb.'01", '.
		'SendingDateTime = "'.$SendAt.'", '.
		'MultiPart = "'.(count($messages) > 0 ? 'true' : 'false').'", '.
		'TextDecoded = "'.$message.'", '.
		'CreatorID = "", '.
		'Class = "-1"');

	$id = $pdo->lastInsertId();
	$i = 1;
	foreach ($messages as $message) {
		$pdo->exec('INSERT INTO outbox_multipart SET '.
			'SequencePosition = "'.++$i.'", '.
			'UDH = "050003'.$ref.$nb.sprintf("%02x", $i).'", '.
			'TextDecoded = "'.$message.'", '.
			'ID = "'.$id.'", '.
			'Class = "-1"');
	}
	die(header('Location: ./'));

?>