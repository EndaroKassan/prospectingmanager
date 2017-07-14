<?php
	session_start();
	if(!isset($_SESSION['userid'])) {
		die("<script type='text/javascript'>
				window.location = 'login.php';
				</script>");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Prospecting Manager</title>
		<link rel='stylesheet' type='text/css' href='css/layout.css'>
		<link rel='stylesheet' type='text/css' href='css/color.css'>
		<link rel='stylesheet' type='text/css' href='css/effect.css'>
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
		<script type='text/javascript' src='js/indexInit.js'></script>
		<script type='text/javascript' src='js/planetFunctions.js'></script>
		<script type='text/javascript' src='js/gridFunctions.js'></script>
		<script type='text/javascript' src='js/usermanagementFunctions.js'></script>
	</head>
	<body>
		<table>
			<tr>
				<td id='leftSideMenu' class='mainSection sideMenu'>
					<img id='sideMenuLogo' class ='sideMenuLogo' src='img/Logo_New_Republic.png' alt='New Republic'  />
					<div id='leftSideMenuGuiFrame' class='guiFrame sideMenuGuiFrame'>
						<label><u>Planet Management</u></label>
						<table id='topMenuTable' class='menuTable'>
							<tr>
								<td id='topMenuLeftCoulmn'>
									<button class='topMenuButton' onclick='loadMap()'>Load Map</button><br />
									<button id='saveMap' class='planetGui topMenuButton' onclick='saveMap()'>Save Map</button><br />
									<button id='deleteMap' class='planetGui topMenuButton' onclick='deleteMap()'>Delete Map</button><br />									
								</td>
								<td id='topMenuRightCoulmn'>
									<label class='topMenuRightElement'>Default Terrain Type: </label>
									<img id='defaultTerrainImg' class='topMenuRightElement mediumImage' alt='' src='' /><br />
									<select id='defaultTerrainTypeSelect'></select>
									<button class='topMenuButton' onclick='newMap()'>Create New Map</button><br />
								</td>
							</tr>
						</table>
						<select id='planetSelect' size='3'></select>
					</div>
				</td>
				<td id='main' class='mainSection'>
					<div id='header'>
						<h1 id='title'><u>Prospecting Manager</u></h1>
						<label id='userName'> Logged in as: </label>
						<form id='logoutForm' action='logout.php' method='post'>
							<input type='submit' value='Log out'>
						</form>
					</div>
					<div class='guiFrame'>
						<table>
							<tr>
								<td id="planetStatsSection">
									<label><u>Planet Stats</u></label><br />
									<label id='idLabel'>ID: </label><br />
									<label id='planetNameLabel'>
										Planet Name: 
										<input id='planetNameInput' class='planetGui' />
									</label><br />
									<label id='planetSizeLabel'>Planet size: </label>
									<input id='planetSizeInput' class='planetGui' type='number' value='1' max='20' min='1' /><br />
									<label id='shareAllLabel'>Share planetmap with all users: </label>
									<input id='shareAllCheckbox' class='planetGui' type='checkbox' /><br />
									<label id='editAllLabel'>Allow other users to save changes: </label>
									<input id='editAllCheckbox' class='planetGui' type='checkbox' />
								</td>
								<td>
									<label><u>User Management</u></label>
									<table>
										<tr>
											<td id='rightSideMenuLeftColumn'>
												<label id='addUserLabel'>New User: </label>
											</td>
											<td id='rightSideMenuRightColumn'>
												<input id='addUserInput' class='userManagementGui' onkeyup='showNames(this.value)' disabled='disabled' />
												<div id='liveSearch'></div>
											</td>
										</tr>
									</table>
									<button id='addUserButton' class='userManagementGui' onclick='addUser()' disabled='disabled'>Add User</button>
									<button id='removeUserButton' class='userManagementGui' onclick='removeUser()' disabled='disabled'>Remove User</button><br />
									<label id='editUserLabel'>
										Allow user to save changes: 
										<input id='editUserCheckbox' class='userManagementGui' type='checkbox' disabled='disabled'  />
									</label><br />
									<select id='userSelect' class='userManagementGui' size=3 disabled='disabled'></select>
								</td>
							</tr>
						</table>
					</div>
					<div id='map'>
					</div>
					<div id='grid' class='guiFrame'>
						<table id='bottomMenuTable' class='menuTable'>
							<tr>
								<td id='bottomMenuUpperLeftCoulmn'>
									<label id='gridCoordsLabel'>Grid Coords: </label>
								</td>
								<td id='bottomMenuUpperRightCoulmn'>
									<label>Terrain Type: </label>
									<select id='terrainTypeSelect' class='gridGui'></select>
									<img id='terrainTypeImg' class='smallImage' alt='' src='' />
								</td>
							</tr>
							<tr>
								<td class='bottomMenuLowerColumn'>
									<label id='gridDepositLabel'>Deposit: 
									<input id='gridDepositSizeInput' class='gridGui'/>
									</label>
								</td>
								<td class='bottomMenuLowerColumn'>
									<select id='depositTypeSelect' class='gridGui' size='5'></select>
									<img id='depositTypeImg' class='bigImage' alt='' src='' />
								</td>
							</tr>
						</table>
					</div>
				</td>
				<td id='rightSideMenu' class='mainSection sideMenu'>
					<img id='rightSideMenuLogo' class ='sideMenuLogo' src='img/Seal_MoNR.png' alt='Ministry of Natural Resources' />
					<div id='rightSideMenuGuiFrame' class='guiFrame sideMenuGuiFrame'>
						<label><u>Deposits</u></label>
						<div id='depositMenu'>
							<table id='depositList'>
							</table>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>