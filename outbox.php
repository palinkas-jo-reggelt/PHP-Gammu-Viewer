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
if($row['Number']=="+19173286699") echo "<td>Brian</td>"; 
  else if($row['Number']=="9173286699") echo "<td>Brian</td>"; 
  else if($row['Number']=="+19176400211") echo "<td>Csilla</td>"; 
  else if($row['Number']=="9176400211") echo "<td>Csilla</td>"; 
  else if($row['Number']=="+15162256673") echo "<td>Eddie</td>"; 
  else if($row['Number']=="5162256673") echo "<td>Eddie</td>"; 
  else if($row['Number']=="+15164688667") echo "<td>Zoe</td>"; 
  else if($row['Number']=="5164688667") echo "<td>Zoe</td>"; 
  else if($row['Number']=="+15164949197") echo "<td>Olivia</td>"; 
  else if($row['Number']=="5164949197") echo "<td>Olivia</td>"; 
  else echo "<td>" . $row['Number'] . "</td>";
echo "<td>" . $row['TextDecoded'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
</div>