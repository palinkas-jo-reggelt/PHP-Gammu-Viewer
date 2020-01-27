<div class="section">
<?php include("config.php") ?>
<?php
	$con = mysqli_connect($Database['host'], $Database['username'], $Database['password'], $Database['dbname']);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$sql = "
		(SELECT 	
			a.ID,
			a.Status,
			a.SendingDateTime,
			a.DestinationNumber AS Number,
			a.TextDecoded,
			a.MultiPart AS Sequence
		FROM outbox a )
		UNION ALL
		(SELECT 
			a.ID,
			a.Status,
			a.SendingDateTime,
			a.DestinationNumber AS Number,
			b.TextDecoded,
			b.SequencePosition AS Sequence
		FROM outbox a JOIN outbox_multipart b
			 on a.ID = b.ID )  
		ORDER BY ID ASC, Sequence ASC
	";
	$res_outbox = mysqli_query($con,$sql);
	echo "<b>CURRENT OUTBOX</b>:<br />";
	echo "<table class='section'>
	<tr>
	<th> </th>
	<th>Send At</th>
	<th>Number</th>
	<th colspan='2'>Message</th>
	</tr>";

	while($row = mysqli_fetch_array($res_outbox)){
		echo "<tr>";

		if (($row['Status']=="SendingOK") || ($row['Status']=="SendingOKNoReport") || ($row['Status']=="Reserved")) {
			echo "<td style='background-color:#008000;color:white;text-align:center;'>O</td>"; 
		} else {
			echo "<td style='color:white; background-color: #FF0000;text-align:center;'>E</td>";
		}

		echo "<td>".date("y/m/d H:i:s", strtotime($row['SendingDateTime']))."</td>";

		$num = str_replace($ServerLocation['CountryCode'], '', $row['Number']);
		if (array_key_exists($num,$Contacts)) {echo "<td>".$Contacts[$num]."</td>";} else {echo "<td>".$num."</td>";}

		$textMsg = preg_replace('(https?:\/\/\S+)', '<a href="$0" target="_blank">$0</a> ', $row['TextDecoded']);
		echo "<td colspan='3'>".$textMsg."</td>";

		echo "</tr>";
	} //mysqli_fetch_array

	echo "<tr>";
	echo "<td colspan='3', style='color:white;background-color:#666666;opacity:0.5;text-align:right;'>Color Key Code:</td>";
	echo "<td style='color:white;background-color:#008000;opacity:0.4;text-align:center;'>O = OK</td>";
	echo "<td style='color:white;background-color:#FF0000;opacity:0.4;text-align:center;'>E = ERROR</td>";
	echo "</tr>";
	echo "</table>";

	mysqli_close($con);
?>
</div>