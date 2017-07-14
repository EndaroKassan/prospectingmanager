function loadGrid(x, y){
	$('.gridGui').prop("disabled", false);
	selectedGrid = [x, y];
	$('#gridCoordsLabel').html("Grid Coords: "+x+" , "+y);
	$('#terrainTypeSelect').val(planet.grids[y][x].terrainType);
	$('#gridDepositSizeInput').val(planet.grids[y][x].depositSize);
	$('#depositTypeSelect').val(planet.grids[y][x].depositType);
	$('#terrainTypeImg').attr('src', terrainTypes[planet.grids[y][x].terrainType].url);
	$('#depositTypeImg').attr('src', depositTypes[planet.grids[y][x].depositType].url);
	displayMap();
}

$(function(){
	$('#terrainTypeSelect').change( function() {
		changed = true;
		$('#terrainTypeImg').attr('src', terrainTypes[this.value].url);
		x = selectedGrid[0];
		y = selectedGrid[1];
		planet.grids[y][x].terrainType = this.value;
		displayMap();
	});

	$('#depositTypeSelect').change( function() {
		changed = true;
		$('#depositTypeImg').attr('src', depositTypes[this.value].url);
		x = selectedGrid[0];
		y = selectedGrid[1];
		planet.grids[y][x].depositType = this.value;
		displayMap();
	});

	$('#gridDepositSizeInput').change( function() {
		changed = true;
		x = selectedGrid[0];
		y = selectedGrid[1];
		planet.grids[y][x].depositSize = this.value;
		displayMap();
	});
});