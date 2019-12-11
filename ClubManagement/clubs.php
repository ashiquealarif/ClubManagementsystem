<?php
	include ("db.php");
	session_start();
	if (isset($_SESSION['user_type'])) {
		$tsql = "SELECT * from users where user_name = '" . $_SESSION['user_name'] . "'";
		$eusers = $conn->query($tsql)->fetchAll();
		if (sizeof($eusers) > 0) {
			
		} else {
			unset($_SESSION['user_name']);
			unset($_SESSION['user_type']);
			unset($_SESSION['user_club']);
			unset($_SESSION['user_id']);
			header( "Location: login.php" );
		}
	} else {
		header( "Location: login.php" );
	}
	
	$title = "Clubs List";
	$class = "clubs-page";
	include ("header.php");
	$sql = '';
	if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
		$sql = 'SELECT * FROM clubs';
	} else {
		$sql = 'SELECT * FROM clubs where club_id = ' . $_SESSION['user_club'];
	}
	$clubs = $conn->query($sql)->fetchAll();
?>	
	<h1 class="main-title">Clubs List</h1>
	<table>
		<tr>
			<th>Club Name</th>
<?php
	if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
		echo '
			<th>Club Members</th>
			<th>Club Events</th>
		';
	}
?>
		</tr>
<?php
	if (sizeof($clubs) > 0) {
		foreach ($clubs as $club) {
			echo
			'
			<tr>
		        <td class="details">
		        	' . $club['CLUB_NAME'] . '
		        </td>
		    ';
		    if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
				echo '
					<td>
						<a href="members.php?club=' . $club['CLUB_ID'] . '" target="_blank">Members List</a>
					</td>
					<td>
						<a href="events.php?club=' . $club['CLUB_ID'] . '" target="_blank">Events List</a>
					</td>
				';
			}
			echo '
				</tr>
			';
		}
	} else {
		echo
			'
			<tr>
		        <td style="text-align: center;">
		        	No drones available right now. Please, check back later.
		        </td>
		    </tr>
		    ';
	}
?>
	</table>
	<div class="clrfix"></div>
<?php
	include ("footer.php");
?>