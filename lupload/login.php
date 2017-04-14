<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);
	
	session_start();
	session_regenerate_id();
	
	if(!isset($_SESSION['username']))
	{
		if (!isset($_POST["username"]) || empty($_POST["username"])) {
			echo "You must input user name!";
			echo "<br /><a href='loginpage.php'>Return to Login page</a>";
			exit();
		}
		if (!isset($_POST["password"]) || empty($_POST["password"])) {
			echo "You must input password!";
			echo "<br /><a href='loginpage.php'>Return to Login page</a>";
			exit();  
		}
		$user=$db->getuser($_POST["username"]);
		if (is_array($user))
		{
			if(!password_verify($_POST["password"], $user["password_sha1"]))
			{
				echo "Login failed!";
				echo "<br /><a href='loginpage.php'>Return to Login page</a>";
			}
			else
			{
				
				$_SESSION['username'] = $user["username"];
				$_SESSION['password'] = $user["password_sha1"];
				//header("Location: index.php");
				if(isset($_SESSION['login_referer']))
				{
					$login_referer = $_SESSION['login_referer'];
					unset($_SESSION['login_referer']);
					header("Location: " . $login_referer);
				}
				else
					header("Location: index.php");
			}
		}
		else
		{
			echo "Login failed!";
			echo "<br /><a href='loginpage.php'>Return to Login page</a>";
		}
		
	}
	else
	{
		unset($_SESSION['login_referer']);
		echo "User ". $_SESSION['username']. " already logged in!";
		echo "<br /><a href='index.php'>Return to Index</a>";
	}
	
?>