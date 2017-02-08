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
			exit();
		}
		if (!isset($_POST["password"]) || empty($_POST["password"])) {
			echo "You must input password!";  
			exit();  
		}
		$olduser=$db->getuser($_POST["username"]);
		if (is_array($olduser))
		{
			echo "User ".$_POST["username"]." already registered!";
		}
		else
		{
			$db->newuser($_POST["username"], $_POST["password"]);
			echo "Register successful!";
		}
		echo "<br /><a href='index.php'>Return to Index</a>";
	}
	else
	{
		echo "Please log out before registering new user!";
	}
	
?>