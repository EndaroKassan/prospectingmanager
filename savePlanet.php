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

	$planet = json_decode($_POST["planet"]);

	//$conn = new mysqli($servername, $username, $password);

	$owner = null;
	$edit = false;
	$userId = $_SESSION['userid'];
	$planetId = $planet->id;
	$size = $planet->size;
	$name = htmlspecialchars ($planet->name);
	$shareAll = $planet->shareAll;
	$editAll = $planet->editAll;
	$grids = $planet->grids;
	$updateGrids = false;
	$insertGrids = true;
	$error = null;
	$dbwrapper = new DbWrapper($servername, $dbUsername, $password, $database);

	try{
		$dbwrapper->open();
	}
	catch(DbConnectException $e){
		die($e->getMessage());
	}

	if ($planetId >= 0){
		$dbwrapper->getAccessLevel($planetId, $owner, $edit);

		if ($owner == $userId){
			if (!$dbwrapper->updatePlanetAsOwner($name, $size, $shareAll, $editAll, $planetId)){
					die("Failed to update planet properties.");
				}
		}
		else{
			if ($edit or ($dbwrapper->isPlanetEditable($planetId, $userId))){
				if (!$dbwrapper->updatePlanetAsShared($name, $size, $planetId)){
					die("Failed to update planet properties.");
				}
			}
			else{
				die("You are not allowed to edit this planet.");
			}
		}
		$dbwrapper->updateGrids($grids, $planetId);
	}
	else{
		if(!$dbwrapper->createPlanet($name, $size, $userId, $shareAll, $editAll)){
			die("Failed to create new planet.");
		}
		$planetId = $dbwrapper->getLastPlanetId($userId);
		$dbwrapper->createGrids($grids, $planetId);
	}

	$dbwrapper->close();

	$output = json_encode($planetId);
	echo "$output";
?>