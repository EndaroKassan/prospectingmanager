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
	$id = $_POST["id"];

	$dbwrapper = new DbWrapper($servername, $dbUsername, $password, $database);
	try{
		$dbwrapper->open();
	}
	catch(DbConnectException $e){
		die($e->getMessage());
	}
	$props = $dbwrapper->getPlanetProperties($id, $userId);
	$grids = $dbwrapper->getGrids($id, $userId);
	$edit = $dbwrapper->isPlanetEditable($id, $userId);
	$dbwrapper->close();

	$obj = (object) array('id' => $id, 'name' => $props[0], 'size' => $props[1],
												'shareAll' => $props[2], 'editAll' => $props[3],
												'owner' => ($props[4] == $userId), 'grids' => $grids, 'edit' => $edit);
	$output = json_encode($obj);
	echo "$output";
?>