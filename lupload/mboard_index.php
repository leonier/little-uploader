<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	require_once 'tools.php';
	$myfile=basename($_SERVER['PHP_SELF']);
	$db = new database($pdo);

	$messageID = $db->selectmessagesid();
	$msgcount = count($messageID);
	$maxpage = ceil($msgcount/$filesperpage);
	if($maxpage==0) $maxpage=1;
	if (empty($_GET['page']))
		$pagenum=1;
	else
		$pagenum=$_GET['page'];
	$msgpage=array_reverse($db->selectmessagespage($messageID, $pagenum, $filesperpage));
	
	session_start();
	session_regenerate_id();
	clearLoginReferer();

?>
<html>
	<head>
        <meta charset="UTF-8">
		<title>MiniUploader Message Board</title>
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
		<?php endif; ?>
		</div>

		<div>
		<?php echo $msgcount; ?> messages, page <?php echo $pagenum; ?>/<?php echo $maxpage; ?> 
		</div>
		<div>
		<a href="<?php echo $myfile;?>?page=1">&lt;&lt;</a>

		<?php if($pagenum>1):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum-1;?>"><?php echo $pagenum-1;?></a>
		<?php endif; ?>

		<?php echo $pagenum;?>

		<?php if($pagenum<$maxpage):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum+1;?>"><?php echo $pagenum+1;?></a>
		<?php endif; ?>

		<a href="<?php echo $myfile;?>?page=<?php echo $maxpage;?>">&gt;&gt;</a>

		</div>

		<table>
			<tr>
				<td>Message Title</td>
				<td>Poster</td>
				<td>Post date</td>
				<td>Operation</td>
			</tr>
			
			<?php foreach($msgpage as $msg): ?>
			<tr>
				<?php if(!empty($msg['title'])): ?>
				<td><a href="mboard_message.php?id=<?php echo $msg['id'];?>"><?php echo $msg['title']; ?></a></td>
				<?php else: ?>
				<td><a href="mboard_message.php?id=<?php echo $msg['id'];?>">(Untitled)</a></td>
				<?php endif; ?>
				<td>
					<?php if(isset($msg['poster'])): ?>
					<?php
						$poster=$db->getuserfromid($msg['poster']);
						echo $poster['username'];
					?>
					<?php else: ?>
					Anonymous
					<?php endif; ?>
				</td>
				<td><?php echo $msg['date_create']; ?></td>
				<td>
					<?php if($islogin==1): ?>
					<?php if(isset($msg['poster'])): ?>
					<?php if($poster['username'] == $_SESSION['username']): ?>
					<a href="mboard_delete.php?id=<?php echo $msg['id'];?>">Delete</a>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>

		<div>
		<a href="<?php echo $myfile;?>?page=1">&lt;&lt;</a>

		<?php if($pagenum>1):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum-1;?>"><?php echo $pagenum-1;?></a>
		<?php endif; ?>

		<?php echo $pagenum;?>

		<?php if($pagenum<$maxpage):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum+1;?>"><?php echo $pagenum+1;?></a>
		<?php endif; ?>

		<a href="<?php echo $myfile;?>?page=<?php echo $maxpage;?>">&gt;&gt;</a>

		</div>
		<?php if($islogin==1):?>
		<div>
		<form action="mboard_create.php" method="post" >
		<div>Message title(optional):<br />
		<input type="text" name="title" id="title">
		</div>
		<div>Message Body:<br />
		<textarea name="mbody" id="mbody"></textarea>
		</div>
		<input type="submit" value="Post message" name="submit">
		</form>
		</div>
		<?php endif; ?>
		<div>
		<a href="index.php">Return to MiniUploader</a>
		</div>
	</body>
</html>