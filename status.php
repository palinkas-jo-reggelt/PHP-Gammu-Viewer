<div class="section">
<?php
	include_once("config.php");
	include_once("functions.php");

	$sql = $pdo->prepare("SELECT UpdatedInDB FROM phones");
	$sql->execute();
	$UpdatedInDB = $sql->fetchColumn();

	$uidbtime = strtotime($UpdatedInDB);
	$curtime = time() - (60*60*6);
	
	if(($curtime - $uidbtime) > 90) {
		echo "<b>STATUS</b>: <span style='color:red;font-weight:bold;'>DISCONNECTED</span>";
	} else {
		echo "<b>STATUS</b>: <span style='color:green;font-weight:bold;'>CONNECTED</span>";
	}
?>
</div>
