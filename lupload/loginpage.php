<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);
	
	session_start();
	session_regenerate_id();
	
?>

<html>
	<head>
        <meta charset="UTF-8">
		<title>Login</title>
	<style>
		table, th, td {
   		border: 1px solid black;
		}
	</style>
	</head>
	<body>
		<?php if(!isset($_SESSION['username'])): ?>
		<p>Login</p>
		<form action="login.php" method="post">
		<p>Username:<input type="text" name="username"  placeholder="Enter Name" maxlength="50"  /></p>
		<p>Password:<input type="password" name="password"  placeholder="Enter Password" maxlength="32" /></p>
		<p><input type="submit" value="Login"></p>
		</form>
		<?php else: ?>
		<p>Already logged in!</p>
		<p><a href="index.php">Return to Index</a></p>
		<?php endif; ?>
		
	</body>
</html>