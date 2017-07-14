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
	$editUserId = $_POST['editUserId'];
	$planetId=$_POST['planetId'];
	$edit = json_decode($_POST['edit']);

	$dbwrapper->getAccessLevel($planetId, $owner, $ed);
	if ($owner == $userId){
		echo "$planetId, $editUserId, $edit";
		$result = $dbwrapper->setUserEdit($planetId, $editUserId, $edit);
	}
	else{
		$result = false;
	}
	$dbwrapper->close();

	echo "$result";
?>