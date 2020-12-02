<?php
	include_once("config.php");
	include_once("functions.php");

	$MobileNumber = $_POST['MobileNumber'];
	$MessagePost = $_POST['Message'];
	$SendAt = $_POST['SendAt'];
	 
	// https://github.com/moulinraphael/gammu/blob/master/sendsms.php
	$num = secure(repairNumber($MobileNumber));
	// $message = utf8_decode(addslashes($MessagePost));
	$message = utf8_decode($MessagePost);
	$messages = str_split($message, 153); 
	$message = array_shift($messages);
	$ref = sprintf("%02x", rand(1, 255));
	$nb = sprintf("%02d", 1 + count($messages));

	//Envoi du SMS demandé
	$sql = $pdo->prepare(
		'INSERT INTO outbox SET '.
		'DestinationNumber = "'.$num.'", '.
		'UDH = "050003'.$ref.$nb.'01", '.
		'SendingDateTime = "'.$SendAt.'", '.
		'MultiPart = "'.(count($messages) > 0 ? 'true' : 'false').'", '.
		'TextDecoded = "'.$message.'", '.
		'CreatorID = "", '.
		'Class = "-1"'
	);
	$sql->execute();

	$id = $pdo->lastInsertId();
	$i = 1;
	foreach ($messages as $message) {
		$sql = $pdo->prepare(
			'INSERT INTO outbox_multipart SET '.
			'SequencePosition = "'.++$i.'", '.
			'UDH = "050003'.$ref.$nb.sprintf("%02x", $i).'", '.
			'TextDecoded = "'.$message.'", '.
			'ID = "'.$id.'", '.
			'Class = "-1"'
		);
		$sql->execute();
	}
	die(header('Location: ./'));

?>