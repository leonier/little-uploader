<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$myfile=basename($_SERVER['PHP_SELF']);
	$db = new database($pdo);

	if (empty($_GET['id']))
	{
		echo "Request Error!";
	}

	$messageID = $_GET['id'];
	$message=$db->selectmessagebyid($messageID);

	if (!is_array($message))
	{
		echo "Request Error!";
		exit();
	}
	$poster=$db->getuserfromid($message['poster']);
	$ownpost=0;
	if(!empty($message['title']))
		$posttitle=$message['title'];
	else
		$posttitle="(Untitled)";
	session_start();
	session_regenerate_id();
?>

<html>
	<head>
        <meta charset="UTF-8">
		<title>MiniUploader Message Board: <?php echo $posttitle; ?></title>
	<style>
		table, th, td {
   		border: 1px solid black;
		}
	</style>
	</head>
	<body>
		<div>
		<?php if(!isset($_SESSION['username'])): ?>
		<p>You must <a href="loginpage.php">Login</a> to post messages.</p>
		<p><a href="register.php">Register</a> new user</p>
		<?php else: ?>
		<p>Logged in as <?php echo $_SESSION['username']; ?> <a href="logout.php">Logout</a></p>
		<?php $islogin=1; ?>
		<?php 
			if(strcmp($poster['username'], $_SESSION['username'])==0)
				$ownpost=1;
		?>
		<?php endif; ?>
		</div>

		<table>
			<tr>
				<td colspan=2>Title:<?php echo $posttitle; ?></td>
			</tr>
			<tr>
				<td>Poster:<?php echo $poster['username']; ?></td>
				<td>Posted on:<?php echo $message['date_create']; ?></td>
			</tr>
			<tr><td colspan=2>Poster IP/Host:<?php echo $message['userip']; ?></td></tr>
			<tr><td colspan=2><?php echo nl2br(htmlentities($message['body'])); ?></td></tr>
			<tr><td colspan=2>
			<?php if($message['date_modify']>0): ?>
			Edited on <?php echo $message['date_modify']; ?>
			<?php endif; ?>		
			<?php if($ownpost==1): ?>
			<a href="mboard_edit.php?id=<?php echo $_GET['id']; ?>">Edit post</a>
			<?php endif; ?>
			</td></tr>			
		</table>
		<?php
			//TODO: process replies
		?>
		<div><a href="mboard_index.php">Message Board</a></div>
	</body>
</html>