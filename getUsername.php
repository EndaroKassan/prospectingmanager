<?php
	session_start();
	if(!isset($_SESSION['userid'])) {
		die('<script type="text/javascript">
				window.location = "login.php";
				</script>');
	}
	echo $_SESSION['username'];