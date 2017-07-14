<?php
	session_start();
	if(isset($_SESSION['userid'])) {
		die('<script type="text/javascript">
    			window.location = "index.php";
				</script>');
	}
	require_once 'include/dbwrapper.php';
	require_once 'include/exceptions.php';
	require_once 'include/dbCredentials.php';

	$dbwrapper = new DbWrapper($servername, $dbUsername, $password, $database);
	try{
		$dbwrapper->open();
	}
	catch(DbConnectException $e){
		die($e->getMessage());
	}

	if(isset($_GET['login'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];

		$userData = $dbwrapper->getUserdata($username);
		$dbwrapper->close();
		if (password_verify($password, $userData["passwordHash"])) {
			if($userData["active"]){
				$_SESSION['userid'] = $userData["id"];
				$_SESSION['username'] = $username;
				die('<script type="text/javascript">
    			window.location = "index.php";
				</script>');
			}
			else {
				$errorMessage = "User not activated<br/>";
			}
		}
		else {
			$errorMessage = "Username not known or password wrong<br/>";
		}
	}
?>
<!DOCTYPE html> 
<html> 
	<head>
		<title>Login</title> 
	</head> 
	<body>

<?php 
	if(isset($errorMessage)) {
		echo $errorMessage;
	}
?>
		<form action="?login=1" method="post">
			Username:<br/>
			<input type="text" size="40" maxlength="250" name="username"><br/>
			<br/>
			Password:<br/>
			<input type="password" size="40"  maxlength="250" name="password"><br/>
			<input type="submit" value="submit">
		</form> 
	</body>
</html>