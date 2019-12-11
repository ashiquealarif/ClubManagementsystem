<?php
	session_start();
	$title = "Logout";
	$class = "logout-page";
	include ("header.php");
?>	
	<h1 class="main-title">Logout</h1>
	<div class="clrfix"></div>
<?php
	unset($_SESSION['user_name']);
	unset($_SESSION['user_type']);
	unset($_SESSION['user_club']);
	unset($_SESSION['user_id']);
	header("Location: login.php");
	include ("footer.php");
?>
