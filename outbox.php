<div class="section">
<?php include("cred.php") ?>
<?php

	$resultob = mysqli_query($con,"SELECT Status, DATE_FORMAT(SendingDateTime, '%y/%m/%d %H:%i.%s') as TimeStamp, DestinationNumber as Number, TextDecoded FROM outbox");

	echo "<b>CURRENT OUTBOX</b>:<br />";
	echo "<table class='section'>
	<tr>
	<th> </th>
	<th>Send At</th>
	<th>Number</th>
	<th>Message</th>
	</tr>";

	echo "<tr>";

	while($row = mysqli_fetch_array($resultob)){
		if($row['Status']=="Reserved") echo "<td style='background-color: #008000;text-align:center;'>R</td>"; 
		  else echo "<td>" . $row['Status'] . "</td>";
		echo "<td>" . $row['TimeStamp'] . "</td>";
		if($row['Number']=="2125551234") echo "<td>John</td>";           // <-Edit name & number to display name
		  else if($row['Number']=="5095559876") echo "<td>Chris</td>";   // <-Edit name & number to display name
		  else if($row['Number']=="4045554567") echo "<td>Mark</td>";    // <-Edit name & number to display name
		  else if($row['Number']=="5615550001") echo "<td>Darren</td>";  // <-Edit name & number to display name
		  else echo "<td>" . $row['Number'] . "</td>";
		echo "<td>" . $row['TextDecoded'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";

	mysqli_close($con);
?>
</div>