<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	require_once 'tools.php';
	
	$myfile=basename($_SERVER['PHP_SELF']);
	$db = new database($pdo);

	if (empty($_GET['uid']))
	{
		echo "Request Error!";
		exit();
	}
	
	$usrlogin=0;
	$user=$db->getuserfromid($_GET['uid']);
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
?>
<html>
	<head>
        <meta charset="UTF-8">
		<title>User Information</title>
	<style>
		table, th, td {
   		border: 1px solid black;
		}
	</style>
	</head>
	<body>
		<?php if($usrlogin!=1): ?>
			<p>Cannot change password!</p>
		<?php else: ?>
			<p>Change password for <?php echo $user['username']; ?></p>
			<form action="change_password_save.php" method="post">
				<p>Old password:<input type="password" name="password_old"  placeholder="Enter password" maxlength="32"  /></p>
				<p>New password:<input type="password" name="password1"  placeholder="Enter password" maxlength="32"  /></p>
				<p>New password (again):<input type="password" name="password2"  placeholder="Enter password again" maxlength="32"  /></p>
				<input type="hidden" name="uid" value="<?php echo $user['id']; ?>" />
				<p><button type="submit">Submit</button></p>
			</form>
		<?php endif; ?>
		<p>
			<?php if(isset($_SERVER['HTTP_REFERER'])): ?>
				<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Return to last page</a>
			<?php else: ?>
				<a href="index.php">Return to index</a>	
			<?php endif; ?>
		</p>
	</body>
</html>