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
	$title = "Members List";
	$class = "members-page";
	include ("header.php");
	$sql = '';
	if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
		if (isset($_GET['club'])) {
			$sql = 'SELECT * FROM members where member_club = ' . $_GET['club'];
		} else {
			$sql = 'SELECT * FROM members';
		}
		
	} else {
		$sql = 'SELECT * FROM members where member_club = ' . $_SESSION['user_club'];
	}
	$members = $conn->query($sql)->fetchAll();
?>	
	<h1 class="main-title">Members List</h1>
	<table>
		<tr>
			<th>Member Name</th>
			<th>ID</th>
			<th>Details</th>
		</tr>
<?php
	if (sizeof($members) > 0) {
		foreach ($members as $member) {
			$sql2 = 'SELECT user_name FROM users where user_profile = ' . $member['MEMBER_PROFILE'];
			$users = $conn->query($sql2)->fetchAll();
			if (sizeof($users) > 0) {
				foreach ($users as $user) {
					$member['MEMBER_ID'] = $user['USER_NAME'];
				}
			}
			$sql3 = 'SELECT profile_name FROM profiles where profile_id = ' . $member['MEMBER_PROFILE'];
			$profiles = $conn->query($sql3)->fetchAll();
			if (sizeof($profiles) > 0) {
				foreach ($profiles as $profile) {
					$member['MEMBER_NAME'] = $profile['PROFILE_NAME'];
				}
			}
			echo
			'
			<tr>
		        <td class="details">
		        	' . $member['MEMBER_NAME'] . '
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_ID'] . '
		        </td>
		        <td class="details">
		        	<a href="member-details.php?member=' . $member['MEMBER_PROFILE'] . '" target="_blank">
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
		        <td colspan="3" style="text-align: center;">
		        	No member joined yet. Please, check back later.
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