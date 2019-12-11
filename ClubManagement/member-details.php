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

	$title = "Member Details";
	$class = "member-details-page";
	include ("header.php");
	function memberType($n) {
		switch ($n) {
			case 1:
				return 'Super Admin';
				break;
			case 2:
				return 'Admin';
				break;			
			case 3:
				return 'President';
				break;
			case 4:
				return 'Executive member';
				break;
			case 5:
				return 'General member';
				break;			
			default:
				return 'Invalid';
				break;
		}
	}
	$sql = '';
	if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
		$sql = 'SELECT * FROM members where member_profile =' . $_GET['member'];
	} elseif(($_SESSION['user_type'] == 'President') || ($_SESSION['user_type'] == 'Executive member')) {
		$sql = 'SELECT * FROM members where member_profile =' . $_GET['member'] . 'and member_club = ' . $_SESSION['user_club'];
	} else {
		$sql = 'SELECT * FROM members where member_profile =' . $_SESSION['user_id'];
	}
	$members = $conn->query($sql)->fetchAll();
?>	
	<h1 class="main-title">Member Details</h1>
	<table>
<?php
	if (sizeof($members) > 0) {
		foreach ($members as $member) {
			$member['MEMBER_TYPE'] = memberType($member['MEMBER_TYPE']);
			$sql2 = 'SELECT user_name FROM users where user_profile = ' . $member['MEMBER_PROFILE'];
			$users = $conn->query($sql2)->fetchAll();
			if (sizeof($users) > 0) {
				foreach ($users as $user) {
					$member['MEMBER_ID'] = $user['USER_NAME'];
				}
			}
			$sql3 = 'SELECT * FROM profiles where profile_id = ' . $member['MEMBER_PROFILE'];
			$profiles = $conn->query($sql3)->fetchAll();
			if (sizeof($profiles) > 0) {
				foreach ($profiles as $profile) {
					$member['MEMBER_NAME'] = $profile['PROFILE_NAME'];
					$member['MEMBER_EMAIL'] = $profile['PROFILE_EMAIL'];
					$member['MEMBER_PHONE'] = $profile['PROFILE_PHONE'];
					$member['MEMBER_ADDRESS'] = $profile['PROFILE_ADDRESS'];
					$member['MEMBER_DOB'] = $profile['PROFILE_DOB'];
					$member['MEMBER_DEPARTMENT'] = $profile['PROFILE_DEPARTMENT'];
					$member['MEMBER_BLOODGROUP'] = $profile['PROFILE_BLOODGROUP'];
					$member['MEMBER_JOINED'] = $profile['PROFILE_JOINED'];
				}
			}
			$sql4 = 'SELECT club_name FROM clubs where club_id = ' . $member['MEMBER_CLUB'];
			$clubs = $conn->query($sql4)->fetchAll();
			if (sizeof($clubs) > 0) {
				foreach ($clubs as $club) {
					$member['MEMBER_CLUB'] = $club['CLUB_NAME'];
				}
			}
			echo
			'
			<tr>
		        <td class="details">
		        	Memeber Name
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_NAME'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	ID
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_ID'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Phone
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_PHONE'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Email
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_EMAIL'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Address
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_ADDRESS'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Phone
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_DEPARTMENT'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Email
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_DOB'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Address
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_BLOODGROUP'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Club Name
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_CLUB'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Member Type
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_TYPE'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Requested By
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_REQUESTED'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Approved By
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_APPROVED'] . '
		        </td>
		    </tr>
		    <tr>
		        <td class="details">
		        	Join Date
		        </td>
		        <td class="details">
		        	' . $member['MEMBER_JOINED'] . '
		        </td>
		    </tr>
		    ';
		}
	} else {
		echo
			'
			<tr>
		        <td style="text-align: center;">
		        	No such member.
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