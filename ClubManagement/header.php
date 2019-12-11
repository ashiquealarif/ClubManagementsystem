<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body class="<?php echo $class; ?>">
	<header>
		<ul class="nav">
			<li>
				<a href="clubs.php">Clubs</a>
			</li>
			<li>
				<a href="members.php">Members</a>
			</li>
			<li>
				<a href="events.php">Events</a>
			</li>
<?php
	if(isset($_SESSION['user_type'])) {
		if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
			echo '
				<li>
					<a href="add-club.php">Add Club</a>
				</li>
			';
		}
		if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin') || ($_SESSION['user_type'] == 'President')) {
			echo '
				<li>
					<a href="add-member.php">Add Member</a>
				</li>
			';
		}
		// if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin') || ($_SESSION['user_type'] == 'President') || ($_SESSION['user_type'] == 'Executive member')) {
		// 	echo '
		// 		<li>
		// 			<a href="add-event.php">Add Event</a>
		// 		</li>
		// 	';
		// }
		
		echo '
			<li>
				<a href="logout.php">Logout</a>
			</li>
		';
	}
?>
		</ul>
	</header>
	<div class="clrfix"></div>