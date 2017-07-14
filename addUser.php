<?php
	session_start();
	if(!isset($_SESSION['userid'])) {
		die('<script type="text/javascript">
				window.location = "login.php";
				</script>');
	}
	require_once 'include/dbwrapper.php';
	require_once 'include/exceptions.php';
	require_once 'include/dbCredentials.php';

	$owner = null;
	$edit = false;
	$result = false;
	$username = $_POST["username"];
	$planetId = $_POST["planetId"];
	$userId = $_SESSION['userid'];

	$dbwrapper = new DbWrapper($servername, $dbUsername, $password, $database);

	try{
		$dbwrapper->open();
	}
	catch(DbConnectException $e){
		die($e->getMessage());
	}

	$dbwrapper->getAccessLevel($planetId, $owner, $edit);
	if ($owner == $userId){
		$addedUserId = $dbwrapper->getUserIdByName($username);
		if ($addedUserId == $owner){
			die("$result");
		}
		else{
			$result = $dbwrapper->addUser($planetId, $addedUserId);
		}
	}

	$dbwrapper->close();
	if ($result){
		echo "$addedUserId";
	}
	else{
		echo "$result";
	}
?>