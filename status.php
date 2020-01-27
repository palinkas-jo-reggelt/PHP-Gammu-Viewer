<div class="section">
<?php include("config.php") ?>
<?php
	$con = mysqli_connect($Database['host'], $Database['username'], $Database['password'], $Database['dbname']);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$query = "SELECT UpdatedInDB FROM phones";
	$result = mysqli_query($con,$query);

	while($row = mysqli_fetch_array($result)){
		$UpdatedInDB = $row['UpdatedInDB'];
	}

	$time = strtotime($UpdatedInDB);
	$curtime = time();
	if(($curtime - $time) > 90) {
		echo "<b>STATUS</b>: <span style='color:red;font-weight:bold;'>DISCONNECTED</span>";
	} else {
		echo "<b>STATUS</b>: <span style='color:green;font-weight:bold;'>CONNECTED</span>";
	}
?>
</div>
