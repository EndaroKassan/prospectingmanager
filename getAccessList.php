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

	$dbwrapper = new DbWrapper($servername, $dbUsername, $password, $database);
	try{
		$dbwrapper->open();
	}
	catch(DbConnectException $e){
		die($e->getMessage());
	}

	$userId = $_SESSION['userid'];
	$planetId=$_GET["planetId"];

	$dbwrapper->getAccessLevel($planetId, $owner, $edit);
	if ($owner == $userId){
		$users = $dbwrapper->getUsersByPlanet($planetId);
	}
	else{
		$users = [];
	}
	$dbwrapper->close();

	$output = json_encode($users);
	echo "$output";
?>