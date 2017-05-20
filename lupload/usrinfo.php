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
		<p>User Information</p>
		<p>User name: <?php echo $user['username']; ?></p>
		<p>Uploaded files: <?php echo $db->countuseruploads($user['id']); ?></p>
		<p>Posted messages: <?php echo $db->countusermessages($user['id']); ?></p>
		<p>Posted replies: <?php echo $db->countuserreplies($user['id']); ?></p>
		<?php if($usrlogin==1): ?>
			<p>User options</p>
			<p><a href="change_password.php?uid=<?php echo $user['id']; ?>">Change password</a></p>
		<?php endif; ?>
		<?php indexPage(); ?>
	</body>
</html>