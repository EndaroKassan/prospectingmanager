function showNames(substring){
	$('#liveSearch').slideDown('fast');
	if (substring.length==0) {
    $('#liveSearch').val("");
    $('#liveSearch').css({'border':'0px'});
    return;
  }
  $('#liveSearch').empty();
	$.ajax({
		url: "findUsers.php",
		type: "GET",
		data: {substring: substring},
		success: function(result){
		var users = JSON.parse(result);
		users.forEach(function(user){
				var line = $('<a>', {text: user.username });
				line.mousedown(function(){
					$('#addUserInput').val(user.username);
				});
				line.mouseover(function(){
					line.css({'background': 'grey'});
				});
				line.mouseout(function(){
					line.css({'background': 'white'});
				});
				$('#liveSearch').append(line);
			});
			$('#liveSearch').attr('size', users.length);

		}
	});
}

function loadAccessList(){
	$('#userSelect').empty();
	$.ajax({
		url: "getAccessList.php",
		type: "GET",
		data: {planetId: planet.id},
		success: function(result){
			var users = JSON.parse(result);
			users.forEach(function(user){
				var option = $('<option>', {value: user.id, text: user.username });
				$('#userSelect').append(option);
			});
		}
	});
}

function setEditUserCheckbox(editUserId){
	$.ajax({
		url: "getEditUserStatus.php",
		type: "GET",
		data: {planetId: planet.id, editUserId: editUserId},
		success: function(result){
			var edit = JSON.parse(result);
			$('#editUserCheckbox').prop('checked', edit);
		}
	});
}

function addUser(){
	var username = $('#addUserInput').val();
	var user = {};
	user.username = username;
	user.edit = false;
	$.ajax({
		url: "addUser.php",
		type: "POST",
		data: {username: username, planetId: planet.id},
		success: function(userId){
			if (userId){
				loadAccessList();
				$('#addUserInput').val("");
			}
			else{
				alert("User " + username + " could not be added");
			}
		}
	});
}

function removeUser(){
	var removedUserId = $('#userSelect').val();
	$.ajax({
		url: "removeUser.php",
		type: "POST",
		data: {removedUserId: removedUserId, planetId: planet.id},
		success: function(result){
			loadAccessList();
		}
	});
}

$(function(){	
	$('#addUserInput').blur( function() {
		$('#liveSearch').slideUp('fast');
	});

	$('#editUserCheckbox').change( function(){
		var editUserId = $('#userSelect').val();
		var edit = this.checked;
		$.ajax({
			url: "changeEditUser.php",
			type: "POST",
			data: {editUserId: editUserId, edit: JSON.stringify(edit), planetId: planet.id},
			success: function(result){
			}
		});
	});

	$('#userSelect').change( function(){
		$('#removeUserButton').prop("disabled", false);
		$('#editUserCheckbox').prop("disabled", false);
		setEditUserCheckbox($(this).val());
	});
});