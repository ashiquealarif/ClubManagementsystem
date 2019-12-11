<?php
	include ("db.php");
	session_start();
	if (isset($_SESSION['user_type'])) {
		$tsql = "SELECT * from users where user_name = '" . $_SESSION['user_name'] . "'";
		$eusers = $conn->query($tsql)->fetchAll();
		if (sizeof($eusers) > 0) {
			header( "Location: clubs.php" );
		} else {
			unset($_SESSION['user_name']);
			unset($_SESSION['user_type']);
			unset($_SESSION['user_club']);
			unset($_SESSION['user_id']);
			header( "Location: login.php" );
		}
	}
	
	$title = "Sign in";
	$class = "login-page";
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
?>	
	<h1 class="main-title">Login</h1>
	<div class="clrfix"></div>
<?php
	$usernameErr = $passwordErr = $username = $password = "";
	$flag[] = array_fill(0, 2, false);
	if (isset($_POST['submit'])) {
		if (empty($_POST["username"])) {
        	$usernameErr = " Username is required";
        	$flag[0] = false;
    	} else {
    		$username = $_POST['username'];
    		$flag[0] = true;
    	}
    	if (empty($_POST["password"])) {
        	$passwordErr = " Password is required";
        	$flag[1] = false;
    	} else {
    		$password = $_POST['password'];
    		$flag[1] = true;
    	}
	    if (($flag[0] == true) && ($flag[1] == true)) {
	    	if(auth($username, $password)) {
				$_SESSION['user'] = $username;
				header( "Location: clubs.php" );
			} else {
				echo '
		            <p class="error-msg">Wrong credentials, try again later!!</p>';
			}
	    }
	}
	function auth($user, $pass){
		global $conn;
		$sql = "SELECT * FROM users where user_name = '" . $user . "' and user_pass = '" . $pass . "'";
		$users = $conn->query($sql)->fetchAll();
		if (sizeof($users) > 0) {
			foreach ($users as $user) {
				$sql2 = 'SELECT club_id FROM clubs where club_id = (SELECT member_club from members where member_profile = ' . $user['USER_PROFILE'] . ')';
				$clubs = $conn->query($sql2)->fetchAll();
				if (sizeof($clubs) > 0) {
					foreach ($clubs as $club) {
						$user['USER_CLUB'] = $club['CLUB_ID'];
					}
				}
				$_SESSION['user_name'] = $user['USER_NAME'];
				$_SESSION['user_type'] = memberType($user['USER_TYPE']);
				$_SESSION['user_club'] = $user['USER_CLUB'];
				$_SESSION['user_id'] = $user['USER_PROFILE'];
				return true;
			}
		} else {
			return false;
		}
	}
	function login() {
		global $username, $usernameErr, $passwordErr;
?>

	<form method="post" action="">
	    <input type="text" name="username" placeholder="Username (required)" value="<?php echo $username; ?>"><span><?php echo $usernameErr; ?></span>
	    <input type="password" name="password" placeholder="Password (required)" value=""><span><?php echo $passwordErr; ?></span>
	    <input type="submit" class="btn black" name="submit" value="Submit">
	</form>
<?php
}
	login();
	include ("footer.php");
?>
