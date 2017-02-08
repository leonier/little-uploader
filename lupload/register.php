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
		<title>Register</title>
	<style>
		table, th, td {
   		border: 1px solid black;
		}
	</style>
	</head>
	<body>
		<?php if(!isset($_SESSION['username'])): ?>
		<p>Register new user</p>
		<form action="register_save.php" method="post">
		<p>Username:<input type="text" name="username"  placeholder="Enter Name" maxlength="50"  /></p>
		<p>Password:<input type="password" name="password"  placeholder="Enter Password" maxlength="32" /></p>
		<p><button type="submit">Register</button></p>
		</form>
		<?php else: ?>
		<p>Please logout before register!</p>
		<?php endif; ?>
		
	</body>
</html>