<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$myfile=basename($_SERVER['PHP_SELF']);
	$db = new database($pdo);
	$islogin = 0;
	$replycnt = 0;
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

	$replies=$db->selectreplies($message['id']);

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
			<tr><td colspan=2>IP/Host:<?php echo $message['userip']; ?></td></tr>
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
		
		<?php foreach($replies as $reply): ?>
		<?php
			$replycnt++;
			$replier=$db->getuserfromid($reply['poster']);
			if(!is_array($replier))
				$repliername="(Deleted user)";
			else
				$repliername=$replier['username'];
			$ownreply=0;
			if(strcmp($replier['username'], $_SESSION['username'])==0)
				$ownreply=1;
		?>
		<div>Reply <?php echo $replycnt; ?>:</div>
		<table>
			<tr>
				<td colspan=2>Title:<?php echo $reply['title']; ?></td>
			</tr>
			<tr>
				<td>Replier:<?php echo $repliername; ?></td>
				<td>Replied on:<?php echo $reply['date_create']; ?></td>
			</tr>
			<tr><td colspan=2>IP/Host:<?php echo $reply['userip']; ?></td></tr>
			<tr><td colspan=2><?php echo nl2br(htmlentities($reply['body'])); ?></td></tr>
			<tr><td colspan=2>
			<?php if($reply['date_modify']>0): ?>
			Edited on <?php echo $reply['date_modify']; ?>
			<?php endif; ?>		
			<?php if($ownreply==1): ?>
			<a href="mboard_editreply.php?id=<?php echo $reply['id']; ?>">Edit reply</a>
			<a href="mboard_deletereply.php?id=<?php echo $reply['id']; ?>">Delete</a>
			
			<?php endif; ?>
			</td></tr>	
		</table>
		<?php endforeach; ?>

		<?php if($islogin==1):?>
		<div>
		<form action="mboard_createreply.php" method="post" >
		<input type="hidden" name="message" value="<?php echo $message['id']; ?>">
		<div>Reply title(optional):<br />
		<input type="text" name="title" id="title" value="Re:<?php echo $posttitle; ?>">
		</div>
		<div>Reply Body:<br />
		<textarea name="mbody" id="mbody"></textarea>
		</div>
		<input type="submit" value="Post reply" name="submit">
		</form>
		</div>
		<?php endif; ?>

		<div><a href="mboard_index.php">Message Board</a></div>
	</body>
</html>