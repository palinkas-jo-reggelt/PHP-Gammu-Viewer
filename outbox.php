<?php
	include_once("config.php");
	include_once("functions.php");

	$ismsg_sql = $pdo->prepare("SELECT COUNT(id) FROM outbox");
	$ismsg_sql->execute();
	$outbox_records = $ismsg_sql->fetchColumn();

	$sql = $pdo->prepare("
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
	");
	$sql->execute();

	if ($outbox_records > 0){
		echo "<div class='section'>";
		echo "<b>CURRENT OUTBOX</b>:<br />";
		echo "<table class='section'>
		<tr>
		<th> </th>
		<th>Send At</th>
		<th>Number</th>
		<th colspan='2'>Message</th>
		</tr>";

		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
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
		}

		echo "<tr>";
		echo "<td colspan='3', style='color:white;background-color:#666666;opacity:0.5;text-align:right;'>Color Key Code:</td>";
		echo "<td style='color:white;background-color:#008000;opacity:0.4;text-align:center;'>O = OK</td>";
		echo "<td style='color:white;background-color:#FF0000;opacity:0.4;text-align:center;'>E = ERROR</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
	}
?>