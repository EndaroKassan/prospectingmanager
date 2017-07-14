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
	$userId = $_SESSION['userid'];
	$planetId = json_decode($_POST["planetId"]);

	$dbwrapper = new DbWrapper($servername, $dbUsername, $password, $database);

	try{
		$dbwrapper->open();
	}
	catch(DbConnectException $e){
		die($e->getMessage());
	}

	$dbwrapper->getAccessLevel($planetId, $owner, $edit);
	if ($owner == $userId){
		$dbwrapper->deletePlanet($planetId);
	}
	$dbwrapper->close();
?>