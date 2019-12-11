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
	$title = "Events Details";
	$class = "events-details-page";
	include ("header.php");
	$sql = 'SELECT * FROM events where event_id = ' . $_GET['event'];
	$sql2 = 'SELECT * FROM profiles where profile_id = (select volunteer_profile from volunteers where volunteer_event = ' . $_GET['event'] . ')';
	$events = $conn->query($sql)->fetchAll();
	$profiles = $conn->query($sql2)->fetchAll();
?>	
	<h1 class="main-title">Event Details</h1>
	<table>
		<tr>
	        <th>
	        	TITLE
	        </td>
	        <th>
	        	DESCRIPTION
	        </th>
	        <th>
	        	APPROVED BY
	        </th>
	        <th>
	        	BUDGET
	        </th>
	        <th>
	        	DATE
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
		        	' . $event['EVENT_DESCRIPTION'] . '
		        </td>
		        <td class="details">
		        	' . $event['EVENT_APPROVED'] . '
		        </td>
		        <td class="details">
		        	' . $event['EVENT_BUDGET'] . '
		        </td>
		        <td class="details">
		        	' . $event['EVENT_DATE'] . '
		        </td>
		    </tr>
		    ';
		}
	} else {
		echo
			'
			<tr>
		        <td colspan="5" style="text-align: center;">
		        	There is no such event.
		        </td>
		    </tr>
		    ';
	}
?>
	</table>
	<div class="clrfix"></div>
	<table>
		<tr>
	        <th>
	        	Volunteer Name
	        </td>
	        <th>
	        	ID
	        </th>
	    </tr>
<?php
	if (sizeof($profiles) > 0) {
		foreach ($profiles as $profile) {
			$sql3 = 'SELECT user_name FROM users where user_profile = ' . $profile['PROFILE_ID'];
			$users = $conn->query($sql3)->fetchAll();
			if (sizeof($users) > 0) {
				foreach ($users as $user) {
					$profile['PROFILE_USERNAME'] = $user['USER_NAME'];
				}
			}
			echo
			'
			<tr>
		        <td class="details">
		        	' . $profile['PROFILE_NAME'] . '
		        </td>
		        <td class="details">
		        	' . $profile['PROFILE_USERNAME'] . '
		        </td>
		    </tr>
		    ';
		}
	} else {
		echo
			'
			<tr>
		        <td colspan="2" style="text-align: center;">
		        	No volunteer found for this event.
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