function displayMap(){
	$('#map').empty();
	$('#depositList').empty();
	var header = $('<tr>');
	header.append($('<th>', {text: "Coords"}));
	header.append($('<th>', {text: "Type"}));
	header.append($('<th>', {text: "Size"}));
	$('#depositList').append(header);

	for (i=0; i<planet.size; i++){
		for (j=0; j<planet.size; j++){
			(function(i,j){
				var img = $('<img>', {class: 'gridImage smallImage', src: terrainTypes[planet.grids[i][j].terrainType].url})
									.css({'top': (i*25)+'px', 'left': (j*25)+'px'});
				$('#map').append(img);

				if (planet.grids[i][j].depositType!=0){
					var deposit = $('<img>', {class: 'gridImage smallImage', src: depositTypes[planet.grids[i][j].depositType].url.slice(0, -3)+'gif'})
												.css({'top': (i*25)+'px', 'left': (j*25)+'px', 'z-index': 1, 'border-style': 'none'});
					$('#map').append(deposit);

					var row = $('<tr>');
					row.append($('<td>', {text: i+","+j}));
					row.append($('<td>', {text: depositTypes[planet.grids[i][j].depositType].name}));
					row.append($('<td>', {text: planet.grids[i][j].depositSize}));
					$('#depositList').append(row);
				}
				var area = $('<div>', {class: 'gridImage smallImage'})
											.css({'top': (i*25)+'px', 'left': (j*25)+'px', 'z-index': 2});
				if (i == selectedGrid[1] && j == selectedGrid[0]){
					area.addClass('selectedImage');
				}
				area.click(function(){loadGrid(j,i);});
				$('#map').append(area);
			})(i,j);
		}
	}
}

function loadMap(){
	loadMapIntern($('#planetSelect').val());
}

function loadMapIntern(planetId){
	if (changed){
		if (!confirm("All changes you made to the current map since the last save will be lost." +
									" Do you want to procede?")){
			return;
		}
	}
	changed = false;
	selectedGrid = [-1, -1];
	$('.gridGui').prop("disabled", true);
	$('#gridCoordsLabel').html("Grid Coords: ");
	$('.planetGui').prop("disabled", false);
	$('#depositTypeSelect').val(0);
	$('#depositTypeImg').attr('src', depositTypes[0].url);
	$('#removeUserButton').prop("disabled", true);
	$('#editUserCheckbox').prop("disabled", true);
	$('.userManagementGui').prop( "disabled", true);

	$.ajax({
		url: "loadPlanet.php",
		type: "POST",
		data: {id: planetId},
		success: function(result){
			planetout = JSON.parse(result);
			planet.users = [];
			planet.id = planetout.id;
			planet.size = planetout.size;
			planet.grids = planetout.grids;
			planet.name = planetout.name;
			planet.shareAll = planetout.shareAll;
			planet.editAll = planetout.editAll;
			planet.edit = planetout.edit;
			planet.owner = planetout.owner;
			selectedGrid = [-1, -1];
			if (!planet.owner){
				$('#shareAllCheckbox').prop( "disabled", true);
				$('#editAllCheckbox').prop( "disabled", true);
				$('#deleteMap').prop( "disabled", true);
			}else{
				$('#shareAllCheckbox').prop( "disabled", false);
				$('#editAllCheckbox').prop( "disabled", false);
				$('#addUserInput').prop( "disabled", false);
				$('#addUserInput').val("");
				$('#addUserButton').prop( "disabled", false);
				$('#userSelect').prop( "disabled", false);
			}
			if (!planet.owner && !planet.editAll && !planet.edit){
				$('#saveMap').prop( "disabled", true);
			}else{
				$('#saveMap').prop( "disabled", false);
			}
			$('#shareAllCheckbox').prop( "checked", planet.shareAll );
			$('#editAllCheckbox').prop( "checked", planet.editAll );
			$('#idLabel').html('ID: '+planet.id);
			$('#planetNameInput').val(planet.name);
			$('#planetSizeInput').val(planet.size);
			loadAccessList();
			displayMap();
		}
	});
}

function newMap(){
	if (changed){
		if (!confirm("All changes you made to the current map since the last save will be lost." +
									" Do you want to procede?")){
			return;
		}
	}
	changed = false;
	selectedGrid = [-1, -1];
	$('.gridGui').prop("disabled", true);
	$('#gridCoordsLabel').html("Grid Coords: ");
	$('.planetGui').prop("disabled", false);
	$('#removeUserButton').prop("disabled", true);
	$('#editUserCheckbox').prop("disabled", true);
	$('#addUserInput').prop( "disabled", true);
	$('#addUserInput').val("");
	$('#addUserButton').prop( "disabled", true);
	$('#userSelect').prop( "disabled", true);
	$('#userSelect').empty();
	$('#deleteMap').prop( "disabled", true);

	planet.users = [];
	planet.id = -1;
	planet.size = 1;
	planet.name = "new planet";
	planet.grids = [];
	var grids = [];
	for (i=0; i<20; i++){
		grids.push([]);
		for (j=0; j<20; j++){
			var grid = new Object();
			grid.terrainType = defaultTerrainType;
			grid.x=j;
			grid.y=i;
			grid.depositType = 0;
			grid.depositSize = 0;
			grids[i][j] = grid;
		}
	}
	planet.grids = grids;
	planet.shareAll = false;
	planet.editAll = false;
	planet.owner = true;
	selectedGrid = [-1, -1];
	$('#shareAllCheckbox').prop( "checked", false );
	$('#editAllCheckbox').prop( "checked", false );
	$('#idLabel').html('ID: '+planet.id);
	$('#planetNameInput').val(planet.name);
	$('#planetSizeInput').val(planet.size);
	displayMap();
}

function saveMap(){
	$.ajax({
		url: "savePlanet.php",
		type: "POST",
		data: {planet: JSON.stringify(planet)},
		success: function(result){
			loadPlanets();
			loadMapIntern(JSON.parse(result));
		}
	});
	changed = false;
}

function deleteMap(){
	if (!confirm("This will delete the currently loaded planet." +
									" Do you want to procede?")){
		return;
	}
	$.ajax({
		url: "deletePlanet.php",
		type: "POST",
		data: {planetId: JSON.stringify(planet.id)},
		success: function(){
			location.reload();
		}
	});
}

$(function(){
	$('#planetNameInput').change( function() {
		planet.name = this.value;
		changed = true;
	});

	$('#shareAllCheckbox').change( function() {
		planet.shareAll = this.checked;
		changed = true;
	});

	$('#editAllCheckbox').change( function() {
		planet.editAll = this.checked;
		changed = true;
	});

	$('#planetSizeInput').change( function() {
		if (this.value > 20){
			this.value = 20;
		}
		else if (this.value < 1){
			this.value = 1;
		}
		planet.size = this.value;
		changed = true;
		displayMap();
	});

	$('#defaultTerrainTypeSelect').change( function() {
		$('#defaultTerrainImg').attr('src', terrainTypes[this.value].url);
		defaultTerrainType = this.value;
	});

});