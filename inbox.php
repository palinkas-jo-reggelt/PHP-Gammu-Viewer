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
		$search_inbox_sql = " WHERE TextDecoded LIKE '%{$search}%' OR SenderNumber LIKE '%{$search}%'";
		$search_outbox_sql = " WHERE TextDecoded LIKE '%{$search}%' OR DestinationNumber LIKE '%{$search}%'";
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

	echo "<div class=\"section\">";
	if ($search==""){
		echo "<b>MESSAGE HISTORY</b>: ".$total_rows." Messages (Page: ".$page." of ".$total_pages.")";
	} else {
		if ($total_rows == 0){
			echo "SEARCH RESULTS: No results for \"<b>".$search."</b>\"";
		} else {
			echo "SEARCH RESULTS: ".$total_rows." Result".$singular." for \"<b>".$search."</b>\" (Page: ".$page." of ".$total_pages.")";
		}
	}
	
	echo "<table class='section'>
		<tr>
			<th> </th>
			<th>Timestamp</th>
			<th>Number</th>
			<th colspan='3'>Message</th>
		</tr>";

	while($row = $sql->fetch(PDO::FETCH_ASSOC)){
		echo "<tr>";

		if($row['Status']=="SendingOKNoReport") { echo "<td style='color:white; background-color: #008000;text-align:center;'>S</td>"; }
			else if ($row['Status']=="SendingError") { echo "<td style='color:white; background-color: #FF0000;text-align:center;'>E</td>"; }
			else { echo "<td style='color:white; background-color: #0000FF;text-align:center;'>I</td>"; }

		echo "<td>".$row['TimeStamp']."</td>";

		$num = str_replace($ServerLocation['CountryCode'], '', $row['Number']);
		if (array_key_exists($num,$Contacts)) {echo "<td>".$Contacts[$num]."</td>";} else {echo "<td>".$num."</td>";}

		if ($UseShortURL) {
			if ($ShortURLSSL){$HTTP = "https://";} else {$HTTP = "http://";}
			$ShortURLRegex = "(".$ShortURLDomain."\/\S+)";
			$textMsg = preg_replace($ShortURLRegex, '<a href="'.$HTTP.'$0" target="_blank">$0</a> ', $row['TextDecoded']);
		} else {
			$textMsg = $row['TextDecoded'];
		}
		echo "<td colspan='3'>".$textMsg."</td>";

		echo "</tr>";
	} 

	echo "<tr>";
	echo "<td colspan='3', style='color:white;background-color:#666666;opacity:0.5;text-align:right;'>Color Key Code:</td>";
	echo "<td style='color:white;background-color:#008000;opacity:0.4;text-align:center;'>S = SENT</td>";
	echo "<td style='color:white;background-color:#0000FF;opacity:0.4;text-align:center;'>I = INBOX</td>";
	echo "<td style='color:white;background-color:#FF0000;opacity:0.4;text-align:center;'>E = ERROR</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td colspan='3'>";
	echo "<ul>";

	if ($page <= 1){echo "<li>First </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=1\">First </a><li>";}
	if ($page <= 1){echo "<li>Prev </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=".($page - 1)."\">Prev </a></li>";}
	if ($page >= $total_pages){echo "<li>Next </li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=".($page + 1)."\">Next </a></li>";}
	if ($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?submit=Search&search=".$search."&page=".$total_pages."\">Last</a></li>";}

	echo "</ul>";
	echo "</td>";
	echo "<td colspan='3' style='text-align:right'>";

	echo "<form action='./' method='GET'>";
	echo "Search Messages: <input type='text' size='20' name='search'><input type='submit' name='submit' value='Search' >";
	echo "</form>";

	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</div>";
?>
