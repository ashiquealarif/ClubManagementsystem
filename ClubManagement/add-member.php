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
	$title = "Add Member";
	$class = "add-member-page";
	include ("header.php");
?>	
	<h1 class="main-title">Add Member</h1>
	<div class="clrfix"></div>
<?php
	$fnameErr = $usernameErr = $phoneErr = $emailErr = $passwordErr = $fname = $username = $phone = $email = $password = $addressErr = $address = "";
    $auth = array_fill(0, 6, false);
    if (isset($_POST['submit'])) {
        if (empty(trim($_POST["fname"]))) {
            $fnameErr = " Full name is required";
            $auth[0] = false;
        }
        else {
            $fname = $_POST["fname"];
            $auth[0] = true;
        }
        if (empty(trim($_POST["username"]))) {
            $usernameErr = " Username is required";
            $auth[1] = false;
        }
        else {
            $username = $_POST["username"];
            $auth[1] = true;
            if (usernameExists($username))  {
                $usernameErr = " Username is already in use, try new";
                $username = "";
                $auth[1] = false;
            }
        }
        if (empty(trim($_POST["email"])))  {
            $emailErr = " Email is required";
            $auth[2] = false;
        } else {
            $email = $_POST["email"];
            $auth[2] = true;
            if (!validateEmail($email))  {
                $emailErr = " Email is not in a valid format";
                $email = "";
                $auth[2] = false;
            }
            if (emailExists($email))  {
                $emailErr = " Email is already in use, try new";
                $email = "";
                $auth[2] = false;
            }

        }
        if (empty(trim($_POST["phone"])))  {
            $phoneErr = " Phone number is required";
            $auth[3] = false;
        } else {
            $phone = $_POST["phone"];
            $auth[3] = true;
            if (!validatePhone($phone))  {
                $phoneErr = " Phone number is not a valid number for BD";
                $phone = "";
                $auth[3] = false;
            }
            if (phoneExists($phone))  {
                $phoneErr = " Phone number is already in use, try new";
                $phone = "";
                $auth[3] = false;
            }
        }
        if (empty($_POST["password"])) {
            $passwordErr = " Password is required";
            $auth[4] = false;
        } elseif (empty($_POST["confpassword"])) {
            $passwordErr = " Confirm Password is required";
            $auth[4] = false;
        } elseif ($_POST["password"] != $_POST["confpassword"]) {
            $passwordErr = " Password didn't match";
            $auth[4] = false;
        } else {
            $password = $_POST["password"];
            $auth[4] = true;
        }
        if (empty(trim($_POST["address"]))) {
            $addressErr = " Address is required";
            $auth[5] = false;
        }
        else {
            $address = $_POST["address"];
            $auth[5] = true;
        }
    }
    function emailExists($temail) {
    	global $conn;
		$tsql = "SELECT * FROM profiles where profile_email = '" . $temail . "'";
        $temails = $conn->query($tsql)->fetchAll();
        if (sizeof($temails) > 0) {
            return true;
        }
        return false;
	}
	function usernameExists($tuser) {
		global $conn;
		$tsql = "SELECT * FROM users WHERE user_name = '" . $tuser . "'";
		$tusers = $conn->query($tsql)->fetchAll();
        if (sizeof($tusers) > 0) {
            return true;
        }
        return false;
	}
    function phoneExists($tphone) {
        global $conn;
        $tsql = "SELECT * FROM profiles where profile_phone = '" . $tphone . "'";
        $tphones = $conn->query($tsql)->fetchAll();
        if (sizeof($tphones) > 0) {
            return true;
        }
        return false;
    }
    function validatePhone($tphone) {
        $pattern = "/^(?:\+?88)?01[1|5-9]\d{8}$/";
        return (bool)preg_match($pattern, $tphone);
    }
	function validateEmail($temail) {
		$pattern = "/[a-zA-Z0-9_\-.+]+@[a-zA-Z0-9\-]+.[a-zA-Z]+/";
		return (bool)preg_match($pattern, $temail);
	}
	function form() {
		global $fnameErr, $usernameErr, $phoneErr, $emailErr, $passwordErr, $fname, $username, $phone, $email, $password, $addressErr, $address, $auth; 
?>
    <form method="post" action="">
        <input type="text" name="fname" placeholder="Full Name (required)" value="<?php echo $fname; ?>"><span><?php echo $fnameErr; ?></span>
        <input type="text" name="email" placeholder="Email (required)" value="<?php echo $email; ?>"><span><?php echo $emailErr; ?></span>
        <input type="text" name="phone" placeholder="Phone number (required)" value="<?php echo $phone; ?>"><span class="phone"><?php echo $phoneErr; ?></span>
        <textarea name="address" placeholder="Address (required)" value="<?php echo $address; ?>"></textarea><span class="address"><?php echo $addressErr; ?></span>
        <input type="text" name="username" placeholder="Username (required)" value="<?php echo $username; ?>"><span><?php echo $usernameErr; ?></span>
        <input type="password" name="password" placeholder="Password (required)" value=""><span><?php echo $passwordErr; ?></span>
        <input type="password" name="confpassword" placeholder="Confirm Password (required)" value="">
        <input class="btn black" type="submit" name="submit" value="Submit">
    </form>
<?php
    }
    $flag = true;
    foreach ($auth as $key) {
    	if (!$key) {
    		$flag = false;
    	}
    }
    if($flag){
    	$conn = mysqli_connect("127.0.0.1", "root", "", "naim");
        $insert = "INSERT INTO users (fullname, email, username, pass, utype, gender) VALUES ('" . $fname . "', '" . $email . "', '" . $username . "', '" . $password . "', 'User', '" . $gender . "')";
        if ($conn->query($insert) === TRUE) {
        	echo '
            	<p class="sucess-msg">Successfully registered!!</p>';
        } else {
        	echo '
            	<p class="error-msg">Bad request, try again later!!</p>';
        }
    } else {
        form();
    }
?>
<?php
	include ("footer.php");
?>
