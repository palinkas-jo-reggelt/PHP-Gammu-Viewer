<div class="section">
<?php include("cred.php") ?>
<?php
$query = "(SELECT UpdatedInDB FROM phones)";
$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result)){
	$UpdatedInDB = $row['UpdatedInDB'];
    }
$time = strtotime($UpdatedInDB);
$curtime = time();
if(($curtime - $time) > 90) {
  echo "STATUS: <span style='color:red;font-weight:bold;'>DISCONNECTED</span>";
}
else {
  echo "<b>STATUS</b>: <span style='color:green;font-weight:bold;'>CONNECTED</span>";
}
?>
</div>
