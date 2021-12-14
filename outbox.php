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
			a.DestinationNumber AS Number,
			a.Status,
			a.SendingDateTime,
			b.TextDecoded,
			b.SequencePosition AS Sequence
		FROM outbox a 
		JOIN outbox_multipart b on a.ID = b.ID )  
		ORDER BY ID ASC, Sequence ASC
	");
	$sql->execute();

	if ($outbox_records > 0){
		echo "
	<div class='section'>
		<b>CURRENT OUTBOX</b>:<br>
		<div class='overborder'>
			<div id='barchart' style='grid-template-columns: 1fr 1fr;'>
				<div style='color:white;background-color:green;'>O = OK</div>
				<div style='color:white;background-color:red;'>E = Error</div>
			</div>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col center narrow'></div>
					<div class='div-table-col timestamp'>TimeStamp</div>
					<div class='div-table-col number'>To</div>
					<div class='div-table-col'>Message</div>
				</div>
		";

		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			echo "
				<div class='div-table-row'>
			";

			if (($row['Status']=="SendingOK") || ($row['Status']=="SendingOKNoReport") || ($row['Status']=="Reserved")) {
				echo "
					<div class='div-table-col green narrow' data-column='Outbox'>O</div>"; 
			} else {
				echo "
					<div class='div-table-col red narrow' data-column='Error'>E</div>";
			}

			echo "	<div class='div-table-col center' data-column='Timestamp'>".date("y/m/d H:i:s", strtotime($row['SendingDateTime']))."</div>";

			$num = str_replace($ServerLocation['CountryCode'], '', $row['Number']);
			if (array_key_exists($num,$Contacts)) {
				echo "	
					<div class='div-table-col number' data-column='To'>".$Contacts[$num]."</div>";
			} else {
				echo "	
					<div class='div-table-col number' data-column='To'>".displayMobileNumber($num)."</div>";
			}

			if ($UseShortURL) {
				if ($ShortURLSSL){$HTTP = "https://";} else {$HTTP = "http://";}
				$ShortURLRegex = "((".$ShortURLDomain.")\/\S+)";
				$textMsg = preg_replace($ShortURLRegex, '<a href="'.$HTTP.'$0" target="_blank">$0</a>', $row['TextDecoded']);
			} else {
				$textMsg = $row['TextDecoded'];
			}
			echo "	<div class='div-table-col' data-column='Message'>".$textMsg."</div>";

			echo "
				</div>"; // End of div-table-row
		}

		echo "
			</div>"; // End of div-table

		echo "
		</div>"; // End of overborder

		echo "
	</div>"; // End of section

	}
?>