<?php
	require_once 'exceptions.php';

	class DbWrapper{

		private $conn;
		private $servername;
		private $dbUsername;
		private $password;
		private $database;

		public function __construct($servername, $dbUsername, $password, $database){
			$this->servername = $servername;
			$this->dbUsername = $dbUsername;
			$this->password = $password;
			$this->database = $database;
		}

		public function open(){
			$this->conn = new mysqli($this->servername, $this->dbUsername, $this->password, $this->database);
			if ($this->conn->connect_error){
				throw new dbConnectException($this->conn->connect_error);
			}
		}

		public function close(){
			$this->conn->close();
		}

		public function getAccessLevel($id, &$owner, &$edit){
			$stmt = $this->conn->prepare("SELECT owner, editall FROM prospectingmanagerdb.planets WHERE id = ?;");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->bind_result($owner, $edit);
			$stmt->fetch();
			$stmt->close();
			return true;
		}

		public function isPlanetEditable($planetId, $userId){
			$stmt = $this->conn->prepare("SELECT edit FROM prospectingmanagerdb.planets2users WHERE planet = ? AND user = ?;");
			$stmt->bind_param("ii", $planetId, $userId);
			$stmt->execute();
			$stmt->bind_result($edit);
			$stmt->fetch();
			$stmt->close();
			return (bool)$edit;
		}

		public function createPlanet($name, $size, $userId, $shareAll, $editAll){
			$stmt = $this->conn->prepare("INSERT INTO prospectingmanagerdb.planets (name, size, owner, shareall, editall ) VALUES (?,?,?,?,?);");
			$stmt->bind_param("siiii", $name, $size, $userId, $shareAll, $editAll);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		public function updatePlanetAsOwner($name, $size, $shareAll, $editAll, $id){
			$stmt = $this->conn->prepare("UPDATE prospectingmanagerdb.planets SET name = ?, size = ?, shareall = ?, editall = ? WHERE id = ?;");
			$stmt->bind_param("siiii", $name, $size, $shareAll, $editAll, $id);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		public function updatePlanetAsShared($name, $size, $id){
			$stmt = $this->conn->prepare("UPDATE prospectingmanagerdb.planets SET name = ?, size = ? WHERE id = ?;");
			$stmt->bind_param("sii", $name, $size, $id);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		public function createGrids($grids, $planetId){
			$stmt = $this->conn->prepare("INSERT INTO prospectingmanagerdb.grids (planet, xcoord, ycoord,
																		terraintype, deposittype, depositsize) VALUES (?,?,?,?,?,?);");
			$stmt->bind_param("iiiiii", $planetId, $xCoord, $yCoord, $terrainType, $depositType, $depositSize);
			for ($i=0; $i < sizeof($grids); $i++) {
				for ($j=0; $j < sizeof($grids[$i]); $j++) {
					$grid = $grids[$i][$j];
					$terrainType = $grid->terrainType;
					$depositType = $grid->depositType;
					$depositSize = $grid->depositSize;
					$xCoord = $grid->x;
					$yCoord = $grid->y;
					$stmt->execute();
				}
			}
			$stmt->close();
			return true;
		}

		public function updateGrids($grids, $planetId){
			$stmt = $this->conn->prepare("UPDATE prospectingmanagerdb.grids SET terraintype = ?, deposittype = ?, depositsize = ?
																		WHERE planet = ? AND xcoord = ? AND ycoord = ?;");
			$stmt->bind_param("iiiiii", $terrainType, $depositType, $depositSize, $planetId, $xCoord, $yCoord);
			for ($i=0; $i < sizeof($grids); $i++) {
				for ($j=0; $j < sizeof($grids[$i]); $j++) {
					$grid = $grids[$i][$j];
					$terrainType = $grid->terrainType;
					$depositType = $grid->depositType;
					$depositSize = $grid->depositSize;
					$xCoord = $grid->x;
					$yCoord = $grid->y;
					$stmt->execute();
				}
			}
			return true;
		}

		public function getPlanetProperties($id, $userId){
			$stmt = $this->conn->prepare("SELECT p.name, p.size, p.shareall, p.editall, p.owner FROM prospectingmanagerdb.planets p
															LEFT JOIN prospectingmanagerdb.planets2users p2u ON p.id = p2u.planet WHERE p.id = ?
															AND (p.owner = ? OR p.shareall = TRUE OR p2u.user = ?);");
			$stmt->bind_param("iii", $id, $userId, $userId);
			$stmt->execute();
			$stmt->bind_result($name, $size, $share, $edit, $owner);
			$stmt->fetch();
			$stmt->close();
			return [$name, $size, $share, $edit, $owner];
		}

		public function getGrids($id, $userId){
			$stmt = $this->conn->prepare("SELECT g.xcoord, g.ycoord, g.terraintype, g.deposittype, g.depositsize 
															FROM prospectingmanagerdb.grids g JOIN prospectingmanagerdb.planets p ON g.planet = p.id
															LEFT JOIN prospectingmanagerdb.planets2users p2u ON p.id = p2u.planet WHERE g.planet = ?
															AND (p.owner = ? OR p.shareall = TRUE OR p2u.user = ?) ORDER BY g.xcoord, g.ycoord;");
			$stmt->bind_param("iii", $id, $userId, $userId);
			$stmt->execute();
			$stmt->bind_result($xCoord, $yCoord, $terrainType, $depositType, $depositSize);
			$grids = [];
			while ($stmt->fetch()){
				$grids[$xCoord][$yCoord] = (object) array('terrainType' => $terrainType, 'x' => $xCoord, 'y' => $yCoord,
																									'depositType' => $depositType, 'depositSize' => $depositSize);
			}
			$stmt->close();
			return $grids;
		}

		public function getPlanets($userId){
			$stmt = $this->conn->prepare("SELECT p.id, p.name, u.username FROM prospectingmanagerdb.planets p
																		JOIN prospectingmanagerdb.users u ON u.id = p.owner LEFT JOIN prospectingmanagerdb.planets2users p2u ON p.id = p2u.planet
																		WHERE p.deleted = 0 AND (p.owner = ? OR p.shareall = TRUE OR p2u.user = ?) ORDER BY p.id;");
			$stmt->bind_param("ii", $userId, $userId);
			$stmt->execute();
			$stmt->bind_result($id, $name, $owner);
			$planets = [];
			while ($stmt->fetch()) {
				array_push($planets, (object) array('id' => $id, 'name' => $name, 'owner' => $owner));
			}
			$stmt->close();
			return $planets;
		}

		public function getUserId($username){
			$stmt = $this->conn->prepare("SELECT id FROM prospectingmanagerdb.users WHERE username = ?;");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
			return $id;
		}

		public function createUser($username, $email, $password_hash){
			$stmt = $this->conn->prepare("INSERT INTO prospectingmanagerdb.users (username, email, password) VALUES (?,?,?)");
			$stmt->bind_param("sss", $username, $email, $password_hash);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		public function getUserdata($username){
			$stmt = $this->conn->prepare("SELECT id, password, active FROM prospectingmanagerdb.users WHERE username = ?;");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($id, $passwordHash, $active);
			$stmt->fetch();
			$stmt->close();
			return ["id" => $id, "passwordHash" => $passwordHash, "active" => $active];
		}

		public function findUsers($substring){
			$stmt = $this->conn->prepare("SELECT id, username FROM prospectingmanagerdb.users WHERE username like CONCAT('%',?,'%') ORDER BY username;");
			$stmt->bind_param("s", $substring);
			$stmt->execute();
			$stmt->bind_result($id, $username);
			$users = [];
			while ($stmt->fetch()){
				array_push($users, (object) array('id' => $id, 'username' => $username));
			}
			$stmt->close();
			return $users;	
		}

		public function addUser($planetId, $userId){
			$stmt = $this->conn->prepare("INSERT INTO prospectingmanagerdb.planets2users (planet, user) VALUES (?, ?);");
			$stmt->bind_param("ii", $planetId, $userId);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		public function removeUser($planetId, $userId){
			$stmt = $this->conn->prepare("DELETE FROM prospectingmanagerdb.planets2users WHERE planet = ? AND user = ?;");
			$stmt->bind_param("ii", $planetId, $userId);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		public function setUserEdit($planetId, $editUserId, $edit){
			$stmt = $this->conn->prepare("UPDATE prospectingmanagerdb.planets2users SET edit = ?
			                             	WHERE planet = ? AND user = ?;");
			$stmt->bind_param("iii", $edit, $planetId, $editUserId);
			$result = $stmt->execute();
			$stmt->close();
			return "$planetId, $editUserId, $edit";
		}

		public function getUserIdByName($username){
			$stmt = $this->conn->prepare("SELECT id FROM prospectingmanagerdb.users WHERE username like ?;");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
			return $id;		
		}

		public function getUsersByPlanet($planetId){
			$users = [];
			$stmt = $this->conn->prepare("SELECT u.id, u.username, p.edit FROM prospectingmanagerdb.planets2users p
			                             	JOIN prospectingmanagerdb.users u ON u.id = p.user WHERE p.planet = ?;");
			$stmt->bind_param("i", $planetId);
			$stmt->execute();
			$stmt->bind_result($id, $username, $edit);
			while ($stmt->fetch()){
				array_push($users, (object) array('id' => $id, 'username' => $username, 'edit' => $edit));
			}
			$stmt->close();
			return $users;
		}

		public function getLastPlanetId($userId = null){
			if ($userId){
				$stmt = $this->conn->prepare("SELECT id FROM prospectingmanagerdb.planets WHERE owner = ? ORDER BY id DESC LIMIT 1;");
				$stmt->bind_param("i", $userId);
			}
			else{
				$stmt = $this->conn->prepare("SELECT id FROM prospectingmanagerdb.planets ORDER BY id DESC LIMIT 1;");	
			}
			$stmt->execute();
			$stmt->bind_result($planetId);
			$stmt->fetch();
			$stmt->close();
			return $planetId;	
		}

		public function deletePlanet($planetId){
			$stmt = $this->conn->prepare("UPDATE prospectingmanagerdb.planets SET deleted = 1 WHERE id = ?;");
			$stmt->bind_param("i", $planetId);
			$result = $stmt->execute();
			$stmt->close();
		}
	}
?>