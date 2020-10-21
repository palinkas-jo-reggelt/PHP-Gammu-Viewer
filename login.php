<?php
	include_once("config.php");
	$passwordfail = false;

	$output = array();
	$splits = explode('/', $_SERVER["REQUEST_URI"]);
	$count = count($splits);
	for ($i = 1; $i < ($count - 1); $i++) {
		$output[] = "/".$splits[$i];
	}
	$folder = implode($output);
	if (!$folder){$path = "/";} else {$path = $folder;}

	if (isset($_POST['submit'])) {
		if (($_POST['username'] === $user_name) && ($_POST['password'] === $pass_word)) {
			if (isset($_POST['rememberme'])) {
				/* Set cookie to last 1 year */
				setcookie('username', $_POST['username'], strtotime( '+'.$cookie_duration.' days' ), $path, $_SERVER["HTTP_HOST"]);
				setcookie('password', md5($_POST['password']), strtotime( '+'.$cookie_duration.' days' ), $path, $_SERVER["HTTP_HOST"]);
			} else {
				/* Cookie expires when browser closes */
				setcookie('username', $_POST['username'], false, $path, $_SERVER["HTTP_HOST"]);
				setcookie('password', md5($_POST['password']), false, $path, $_SERVER["HTTP_HOST"]);
			}
			header("Location: ".$path);
		} else {
			$passwordfail = true; 
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Log In</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control">
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Remember Me </label>
                <input type="checkbox" name="rememberme" value="1">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
	<?php 
		if ($passwordfail){
			echo "<script>";
			echo "alert('Username/Password Invalid');";
			echo "</script>";
		}
	?>
    </div>    
</body>
</html>