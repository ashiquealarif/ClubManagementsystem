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
	$title = "Events List";
	$class = "events-page";
	include ("header.php");
	$sql = '';
	if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
		if (isset($_GET['club'])) {
			$sql = 'SELECT * FROM events where event_club = ' . $_GET['club'] . ' order by event_date';
		} else {
			$sql = 'SELECT * FROM events order by event_date';
		}
	} else {
		$sql = 'SELECT * FROM events where event_club = ' . $_SESSION['user_club'] . ' order by event_date';
	}
	$events = $conn->query($sql)->fetchAll();
?>	
	<h1 class="main-title">Events List</h1>
	<table>
		<tr>
	        <th>
	        	TITLE
	        </td>
	        <th>
	        	DATE
	        </th>
	        <th>
	        	Action
	        </th>
	    </tr>
<?php
	if (sizeof($events) > 0) {
		foreach ($events as $event) {
			echo
			'
			<tr>
		        <td class="details">
		        	' . $event['EVENT_NAME'] . '
		        </td>
		        <td class="details">
		        	' . $event['EVENT_DATE'] . '
		        </td>
		        <td class="details">
		        	<a href="event-details.php?event=' . $event['EVENT_ID'] . '" target="_blank">
		        		details
		        	</a>
		        </td>
		    </tr>
		    ';
		}
	} else {
		echo
			'
			<tr>
		        <td colspan="5" style="text-align: center;">
		        	No events available right now. Please, check back later.
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