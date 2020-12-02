<div class="section">
<?php
	include_once("config.php");
	include_once("functions.php");

	$sql = $pdo->prepare("SELECT UpdatedInDB FROM phones");
	$sql->execute();
	$UpdatedInDB = $sql->fetchColumn();
	$UIDB = strtotime($UpdatedInDB." ".$TimeZone);
	
	if ((time() - $UIDB) > 90) {
		echo "<b>STATUS</b>: <span style='color:red;font-weight:bold;'>DISCONNECTED</span>";
	} else {
		echo "<b>STATUS</b>: <span style='color:green;font-weight:bold;'>CONNECTED</span>";
	}
?>
</div>
