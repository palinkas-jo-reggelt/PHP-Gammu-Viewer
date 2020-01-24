<div class="section">
<?php include("cred.php") ?>
<?php

	$resultob = mysqli_query($con,"
		(SELECT 	
			a.ID,
			a.Status,
			a.SendingDateTime,
			a.DestinationNumber,
			a.TextDecoded,
			a.MultiPart AS Sequence
		FROM outbox a )
		UNION ALL
		(SELECT 
			a.ID,
			a.Status,
			a.SendingDateTime,
			a.DestinationNumber,
			b.TextDecoded,
			b.SequencePosition AS Sequence
		FROM outbox a JOIN outbox_multipart b
			 on a.ID = b.ID )  
		ORDER BY ID ASC, Sequence ASC
	");

	echo "<b>CURRENT OUTBOX</b>:<br />";
	echo "<table class='section'>
	<tr>
	<th> </th>
	<th>Send At</th>
	<th>Number</th>
	<th colspan='2'>Message</th>
	</tr>";

	echo "<tr>";

	while($row = mysqli_fetch_array($resultob)){
		if (($row['Status']=="SendingOK") || ($row['Status']=="SendingOKNoReport") || ($row['Status']=="Reserved")) echo "<td style='background-color:#008000;color:white;text-align:center;'>O</td>"; 
		  else echo "<td style='color:white; background-color: #FF0000;text-align:center;'>E</td>";
		echo "<td>".date("y/m/d H:i.s", strtotime($row['SendingDateTime']))."</td>";
		if($row['DestinationNumber']=="2125551234") echo "<td>John</td>";           // <-Edit name & number to display name
		  else if($row['DestinationNumber']=="5095559876") echo "<td>Chris</td>";   // <-Edit name & number to display name
		  else if($row['DestinationNumber']=="4045554567") echo "<td>Mark</td>";    // <-Edit name & number to display name
		  else if($row['DestinationNumber']=="5615550001") echo "<td>Darren</td>";  // <-Edit name & number to display name
		  else echo "<td>".$row['DestinationNumber']."</td>";
		$textMsg = preg_replace('(https?:\/\/\S+)', '<a href="$0" target="_blank">$0</a> ', $row['TextDecoded']);
		echo "<td colspan='3'>".$textMsg."</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td colspan='3', style='color:white;background-color:#666666;opacity:0.5;text-align:right;'>Color Key Code:</td>";
	echo "<td style='color:white;background-color:#008000;opacity:0.4;text-align:center;width:50%;'>O = OK</td>";
	echo "<td style='color:white;background-color:#FF0000;opacity:0.4;text-align:center;width:50%;'>E = ERROR</td>";
	echo "</tr>";
	echo "</table>";

	mysqli_close($con);
?>
</div>