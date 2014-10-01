<?php
require "includes/variables.php";
session_start();
$loginStatus = null; //holds the login status (ie login failed, etc)

/*
 * before the user logs in, this page is treated as a login page.
 * when the user logs in he/she will be redirected to another page and this page will be turned into a logout page
 * returning to this page again will log the user out
 */
if(isset($_SESSION['valid_user'])) {
	$loginStatus = "You have been logged out";
	unset($_SESSION['valid_user']);
	session_destroy();	
}

//this portion validates the user's credentials
if(isset($_POST['uid']) && isset($_POST['pw'])) {
	require "includes/db-equip-connect.php"; //database connection settings
	
	//if the get_magic_quotes_gpc plugin is active (usually active by default in older PHP configs, strip the slashes and use MySQL's real_escape_string function instead
	if (get_magic_quotes_gpc()) {
		$uid = $db->real_escape_string(stripslashes($_POST['uid']));
		$pw = $db->real_escape_string(stripslashes($_POST['pw']));		
	}
	//if the plugin is not active then just use MySQL's function
	else {
		$uid = $db->real_escape_string($_POST['uid']);
		$pw = $db->real_escape_string($_POST['pw']);			
	}
	
	//check if the input has a match in the authentication database
	$auth = $db->query("SELECT * FROM ".DB_EQUIP_AUTH." WHERE user='".$uid."' AND password='".$pw."'");
	$db->close();
	//if there's a match, validation the user and redirect him/her to the control panel page
	if ($auth->num_rows) {
		$auth = $auth->fetch_assoc();
		$_SESSION['valid_user'] = $uid;
		header("Location: ".EQUIPMENT_CONTROL_PAGE);
		exit();			
	}
	//if the input produced no matches, update the login status and prompt the user to try again
	else {
		$loginStatus = "Login failed, please try again";
	}	
}
require "includes/header.php";
?>

<!--article-->
<article>
	<h1>Login</h1>
	<form action="authentication.php" method="post">
		<table class="loginTable">
			<tr><td>Username:</td><td><input type="text" name="uid" maxlength="16" /></td></tr> <!--username field-->
			<tr><td>Password:</td><td><input type="password" name="pw" maxlength="16" /></td></tr> <!--password field-->
			<tr><td colspan="2"><button type="submit">Submit</button></td></tr> <!--submit button-->
			<tr><td colspan="2"><?php echo $loginStatus; ?></td></tr> <!--login status container-->
		</table>
	</form>
</article>
<?php require "includes/footer.php"; ?>