<?php include("cred.php") ?>
<?php
	// Escape user inputs for security

	$MobileNumber = mysqli_real_escape_string($con, $_POST['MobileNumber']);
	$Message = mysqli_real_escape_string($con, $_POST['Message']);
	$SendAt = mysqli_real_escape_string($con, $_POST['SendAt']);
	 
	// attempt insert query execution
	$sql = "INSERT INTO outbox (SendingDateTime, DestinationNumber, TextDecoded) VALUES ('$SendAt', '$MobileNumber', '$Message')";
	if(mysqli_query($con, $sql)){
	//    echo "Records added successfully.";
		header('Location: ./');
	} else{
		echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}
	 
	// close connection
	mysqli_close($con);
?>