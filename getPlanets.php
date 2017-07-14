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

	$userId = $_SESSION['userid'];
	$dbwrapper = new DbWrapper($servername, $dbUsername, $password, $database);
	try{
		$dbwrapper->open();
	}
	catch(DbConnectException $e){
		die($e->getMessage());
	}

	$planets = $dbwrapper->getPlanets($userId);

	$dbwrapper->close();

	$output = json_encode($planets);
	echo "$output";
?>