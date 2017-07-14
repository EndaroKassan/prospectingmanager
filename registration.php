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
?>
<!DOCTYPE html>
<html> 
	<head>
		<title>Registration</title>
	</head>
	<body>

<?php
	$showFormular = true;

	if(isset($_GET['register'])) {
		$error = false;
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo 'Please use a valid email address<br>';
			$error = true;
		}
		if(strlen($password) == 0) {
			echo 'Please enter a password<br>';
			$error = true;
		}
		if($password != $password2) {
			echo 'Passwords don\'t match<br>';
			$error = true;
		}

		if(!$error) {
			$id = $dbwrapper->getUserId($username);
			if($id){
				echo 'Username already in use';
				$error = true;
			}
		}

		if(!$error) {
			$password_hash = password_hash($password, PASSWORD_DEFAULT);
			$result = $dbwrapper->createUser($username, $email, $password_hash);
			if($result) {
				$dbwrapper->close();
				echo '<script type="text/javascript">
							window.location = "login.php";
							</script>';
				$showFormular = false;
			}
			else {
				echo 'An error occured. User could not be created<br>';
			}
		}
	}

	if($showFormular) {
?>

		<form action="?register=1" method="post">
			Username:<br>
			<input type="text" size="40" maxlength="250" name="username"><br><br>

			Email:<br>
			<input type="email" size="40" maxlength="250" name="email"><br><br>

			Password:<br>
			<input type="password" size="40"  maxlength="250" name="password"><br>

			Repeat password:<br>
			<input type="password" size="40" maxlength="250" name="password2"><br><br>

			<input type="submit" value="submit">
		</form>

<?php
	}
$dbwrapper->close();
?>

	</body>
</html>