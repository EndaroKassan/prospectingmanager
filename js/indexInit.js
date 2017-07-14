var planet = {};
var map = "";
var defaultTerrainType = 0;
var selectedGrid = [-1, -1];
var changed = false;

var terrainTypes = [{name:"Forest", id:0, url:"img/terrains/forest.gif"},
			{name:"Jungle", id:1, url:"img/terrains/jungle.gif"},
			{name:"Rock", id:2, url:"img/terrains/rock.gif"},
			{name:"Cave", id:3, url:"img/terrains/cave.gif"},
			{name:"Gas Giant", id:4, url:"img/terrains/gasgiant.gif"},
			{name:"Mountain", id:5, url:"img/terrains/mountain.gif"},
			{name:"Crater", id:6, url:"img/terrains/crater.gif"},
			{name:"Glacier", id:7, url:"img/terrains/glacier.gif"},
			{name:"Ocean", id:8, url:"img/terrains/ocean.gif"},
			{name:"Swamp", id:9, url:"img/terrains/swamp.gif"},
			{name:"Desert", id:10, url:"img/terrains/desert.gif"},
			{name:"Grassland", id:11, url:"img/terrains/grassland.gif"},
			{name:"River", id:12, url:"img/terrains/river.gif"},
			{name:"Volcanic", id:13, url:"img/terrains/volcanic.gif"}];

var depositTypes = [{name:"None", id:0, url:""},
			{name:"Quantum", id:1, url:"img/deposits/quantum.jpg"},
			{name:"Meleenium", id:2, url:"img/deposits/meleenium.jpg"},
			{name:"Ardanium", id:3, url:"img/deposits/ardanium.jpg"},
			{name:"Rudic", id:4, url:"img/deposits/rudic.jpg"},
			{name:"Ryll", id:5, url:"img/deposits/ryll.jpg"},
			{name:"Duracrete", id:6, url:"img/deposits/duracrete.jpg"},
			{name:"Alazhi", id:7, url:"img/deposits/alazhi.jpg"},
			{name:"Laboi", id:8, url:"img/deposits/laboi.jpg"},
			{name:"Adegan", id:9, url:"img/deposits/adegan.jpg"},
			{name:"Rockivory", id:10, url:"img/deposits/rockivory.jpg"},
			{name:"Tibannagas", id:11, url:"img/deposits/tibannagas.jpg"},
			{name:"Nova", id:12, url:"img/deposits/nova.jpg"},
			{name:"Varium", id:13, url:"img/deposits/varium.jpg"},
			{name:"Varmigio", id:14, url:"img/deposits/varmigio.jpg"},
			{name:"Lommite", id:15, url:"img/deposits/lommite.jpg"},
			{name:"Hibridium", id:16, url:"img/deposits/hibridium.jpg"},
			{name:"Durelium", id:17, url:"img/deposits/durelium.jpg"},
			{name:"Lowickan", id:18, url:"img/deposits/lowickan.jpg"},
			{name:"Vertex", id:19, url:"img/deposits/vertex.jpg"},
			{name:"Berubian", id:20, url:"img/deposits/berubian.jpg"},
			{name:"Bacta", id:21, url:"img/deposits/bacta.jpg"}];

function loadPlanets(){
	$.ajax({
		url: "getPlanets.php",
		type: "GET",
		success: function(result){
			$('#planetSelect').empty();
			var planets = JSON.parse(result);
			planets.forEach(function(planet){
				var text = planet.id + ": " + planet.name + " (" + planet.owner + ")";
				$('#planetSelect').append($('<option>', {value: planet.id, text: text }));
			});
		}
	});
}

function loadDepositTypes(){
	depositTypes.forEach(function(type){
		var option = $('<option>', {value: type.id, text: type.name, });
		if (type.id != 0){
			option.css({'background-image':'url('+type.url.slice(0, -3)+'gif)', 'background-repeat': 'no-repeat', 'background-position': 'left', 'padding-left': '30px'});
		}
		$('#depositTypeSelect').append(option);
	});	
}

function loadTerrainTypes(){
		terrainTypes.forEach(function(type){
		$('#terrainTypeSelect').append($('<option>', {value: type.id, text: type.name}));
		$('#defaultTerrainTypeSelect').append($('<option>', {value: type.id, text: type.name}));
	});
}

function getUserName(){
	$.ajax({
		url: "getUsername.php",
		type: "GET",
		success: function(result){
			$('#userName').html("Logged in as: " + result);
		}
	});	
}

window.onload = function(){
	$('.gridGui').prop("disabled", true);
	$('.planetGui').prop("disabled", true);
	$('.userManagementGui').prop("disabled", true);

	$('#defaultTerrainImg').attr('src', terrainTypes[0].url);

	getUserName();
	loadTerrainTypes();
	loadDepositTypes();
	loadPlanets();
}