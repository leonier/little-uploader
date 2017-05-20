<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	require_once 'tools.php';
	
	$myfile=basename($_SERVER['PHP_SELF']);
	$db = new database($pdo);

	if (empty($_POST['uid']))
	{
		echo "Request Error!";
		exit();
	}
	
	$usrlogin=0;
	$user=$db->getuserfromid($_POST['uid']);
	if (!is_array($user))
	{
		echo "Request Error!";
		exit();
	}
		
	session_start();
	session_regenerate_id();
	
	if(isset($_SESSION['username']))
	{
		$curuser=$db->getuser($_SESSION['username']);
		if($curuser['id']==$user['id'])
			$usrlogin=1;
	}
	
	if($usrlogin!=1)
	{
		echo "Request Error!";
		exit();		
	}

	if (empty($_POST['password_old']))
	{
		echo "<p>You must input old password!</p>";
		lastPage();
		exit();
	}
	if (!password_verify($_POST["password_old"], $user["password_sha1"]))
	{
		echo "<p>You must input correct old password!</p>";
		lastPage();
		exit();
	}
	if (empty($_POST['password1'])||empty($_POST['password2']))
	{
		echo "<p>You must input new password!</p>";
		lastPage();
		exit();
	}	
	if(strcmp($_POST["password1"],$_POST["password2"]))
	{
		echo "<p>Two new passwords are different!</p>";
		lastPage();
		exit();		
	}
	if(!strcmp($_POST["password1"],$_POST["password_old"]))
	{
		echo "<p>New password must be different with old one!</p>";
		lastPage();
		exit();		
	}
	if(!isPasswordSane($_POST["password1"]))
	{
		echo "<p>New password is too simple and sometimes naive!</p>";//+1s
		lastPage();
		exit();		
	}
	$newsha1=$db->updateuserpassword($user['id'], $_POST["password1"]);
	$_SESSION['password'] = $newsha1;
	echo "<p>Password updated!</p>";
	userInfoPage($user['id']);
?>