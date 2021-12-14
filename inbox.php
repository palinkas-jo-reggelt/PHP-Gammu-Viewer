<?php
	include_once("config.php");
	include_once("functions.php");

	if (isset($_GET['page'])) {$page = $_GET['page'];} else {$page = 1;}
	if (isset($_GET['submit'])) {$button = $_GET ['submit'];} else {$button = "";}
	if (isset($_GET['search'])) {$search = preg_replace('/\s+/', ' ',trim($_GET['search']));} else {$search = "";}
	
	if ($search==""){
		$search_inbox_sql = "";
		$search_outbox_sql = "";
	} else {
		$search_inbox_sql = " WHERE TextDecoded LIKE '%".$search."%' OR SenderNumber LIKE '%".$search."%'";
		$search_outbox_sql = " WHERE TextDecoded LIKE '%".$search."%' OR DestinationNumber LIKE '%".$search."%'";
	}
	
	$offset = ($page-1) * $no_of_records_per_page;
	$total_pages_sql = $pdo->prepare("
		SELECT
			Sum( a.count )
		FROM(
			SELECT 
				Count( * ) AS count 
			FROM inbox
			".$search_inbox_sql."
			UNION ALL
			SELECT 
				Count( * ) AS count 
			FROM sentitems
			".$search_outbox_sql."
		) a
	");
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sql = $pdo->prepare("
		SELECT 
			Status, 
			DATE_FORMAT(ReceivingDateTime, '%y/%m/%d %H:%i:%s') as TimeStamp, 
			SenderNumber as Number, 
			TextDecoded 
		FROM inbox
		".$search_inbox_sql." 
		UNION ALL 
		SELECT 
			Status, 
			DATE_FORMAT(SendingDateTime, '%y/%m/%d %H:%i:%s') as TimeStamp, 
			DestinationNumber as Number, 
			TextDecoded 
		FROM sentitems
		".$search_outbox_sql." 
		ORDER BY TimeStamp DESC  
		LIMIT ".$offset.", ".$no_of_records_per_page
	);
	$sql->execute();

	if ($total_rows == 1) {$singular = '';} else {$singular= 's';}

	echo "
	<div class='section'>";
	
	if ($search == ""){
		echo "<b>MESSAGE HISTORY</b>: ".$total_rows." Messages (Page: ".$page." of ".$total_pages.")";
	} else {
		if ($total_rows == 0){
			echo "<b>SEARCH RESULTS</b>: No results for \"<b>".$search."</b>\"";
		} else {
			echo "<b>SEARCH RESULTS</b>: ".$total_rows." Result".$singular." for \"<b>".$search."</b>\" (Page: ".$page." of ".$total_pages.")";
		}
	}
	
	echo "
		<div class='overborder'>
			<div id='barchart' style='grid-template-columns: 1fr 1fr 1fr;'>
				<div style='color:white;background-color:green;'>S = Sent Items</div>
				<div style='color:white;background-color:blue;'>I = Inbox</div>
				<div style='color:white;background-color:red;'>E = Error</div>
			</div>
			<div class='div-table'>
				<div class='div-table-row-header'>
					<div class='div-table-col center narrow'></div>
					<div class='div-table-col timestamp'>TimeStamp</div>
					<div class='div-table-col number'>Number</div>
					<div class='div-table-col'>Message</div>
				</div>
	";

	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "
				<div class='div-table-row'>
		";

		if($row['Status']=="SendingOKNoReport") {
			echo "	<div class='div-table-col green narrow center' data-column='Sent'><span class='greenbox'>S</span></div>";
			$tofrom = "To";
		} else if ($row['Status']=="SendingError") {
			echo "	<div class='div-table-col red narrow center' data-column='Error'><span class='redbox'>E</span></div>";
			$tofrom = "To";
		} else {
			echo "	<div class='div-table-col blue narrow center' data-column='Inbox'><span class='bluebox'>I</span></div>";
			$tofrom = "From";
		}

		echo "		<div class='div-table-col center timestamp' data-column='Timestamp'>".$row['TimeStamp']."</div>";

		$num = str_replace($ServerLocation['CountryCode'], '', $row['Number']);
		if (array_key_exists($num,$Contacts)) {
			echo "	<div class='div-table-col number' data-column='".$tofrom."'>".$Contacts[$num]."</div>";
		} else {
			echo "	<div class='div-table-col number' data-column='".$tofrom."'>".displayMobileNumber($num)."</div>";
		}

		if ($UseShortURL) {
			if ($ShortURLSSL){$HTTP = "https://";} else {$HTTP = "http://";}
			$ShortURLRegex = "((".$ShortURLDomain.")\/\S+)";
			$textMsg = preg_replace($ShortURLRegex, '<a href="'.$HTTP.'$0" target="_blank">$0</a>', $row['TextDecoded']);
		} else {
			$textMsg = $row['TextDecoded'];
		}
		echo "		<div class='div-table-col' data-column='Message'>".$textMsg."</div>";

		echo "
				</div>"; // End of div-table-row
	} 

			
	echo "
			</div>"; //End of div-table
			
	echo "
			<div class='secleft'>
	";

	if ($page <= 1){echo "<li>First </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=1\">First </a><li>";}
	if ($page <= 1){echo "<li>Prev </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=".($page - 1)."\">Prev </a></li>";}
	if ($page >= $total_pages){echo "<li>Next </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=".($page + 1)."\">Next </a></li>";}
	if ($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=".$total_pages."\">Last</a></li>";}

	echo "
			</div>
			<div class='secright'>
				<form action='".$_SERVER["REQUEST_URI"]."' method='GET'>
					<input type='text' size='20' name='search'> <input type='submit' name='submit' value='Search' >
				</form>
			</div>
			<div class='clear'></div>";

	echo "
		</div>"; //End of overborder

	echo "
	</div>"; //End of section

?>
