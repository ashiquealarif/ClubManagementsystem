<?php
	include ("db.php");
	session_start();
	if (isset($_SESSION['user_type'])) {
		$tsql = "SELECT * from users where user_name = '" . $_SESSION['user_name'] . "'";
		$eusers = $conn->query($tsql)->fetchAll();
		if (sizeof($eusers) > 0) {
			if(($_SESSION['user_type'] == 'Super Admin') || ($_SESSION['user_type'] == 'Admin')) {
				
			} else {
				header( "Location: clubs.php" );
			}
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
	
	$title = "Add Club";
	$class = "add-club-page";
	include ("header.php");
?>	
	<h1 class="main-title">Add Club</h1>
	<div class="clrfix"></div>
<?php
	$clubnameErr = $clubname = "";
	$flag[] = array_fill(0, 1, false);
	if (isset($_POST['submit'])) {
		if (empty($_POST["clubname"])) {
        	$clubnameErr = " Clubname is required";
        	$flag[0] = false;
    	} else {
    		$clubname = $_POST['clubname'];
    		$flag[0] = true;
    		if (clubExists($clubname)) {
    			$clubnameErr = " Clubname already exists, try new.";
        		$flag[0] = false;
    		}
    	}
    	global $conn;
	    if ($flag[0]) {
	    	$sql = "
				BEGIN 
				   insertClubs('" . $clubname . "');
				END; 
			";
			$stmt = $conn->prepare($sql);
	    	if ($stmt->execute()) {
	    		header( "Location: clubs.php" );
			} else {
				echo '
		            <p class="error-msg">Bad Request, try again later!!</p>';
			}
	    }
	}
	function clubExists($club) {
		global $conn;
    	$tsql = "SELECT * FROM clubs where club_name = '" . $club . "'";
    	$tclubs = $conn->query($tsql)->fetchAll();
		if (sizeof($tclubs) > 0) {
			return true;
		}
		return false;
	}
	function addClub() {
		global $clubname, $clubnameErr;
?>

	<form method="post" action="">
	    <input type="text" name="clubname" placeholder="Clubname (required)" value="<?php echo $clubname; ?>"><span><?php echo $clubnameErr; ?></span>
	    <input type="submit" class="btn green" name="submit" value="Submit">
	</form>
<?php
}
	addClub();
	include ("footer.php");
?>