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
	if(!empty($message['title']))
		$posttitle=$message['title'];
	else
		$posttitle="";
	session_start();
	session_regenerate_id();
?>

<html>
	<head>
        <meta charset="UTF-8">
		<title>Edit Message</title>
	<style>
		table, th, td {
   		border: 1px solid black;
		}
		textarea {
		width: 100%;
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

		<form action="mboard_edit_save.php" method="post">
		<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		<table>
			<tr>
				<td>Title(Optional):<input type="text" name="title" id="title" value="<?php echo $posttitle; ?>"></td>
			</tr>
			<tr><td>Content:</td></tr>
			<tr><td><textarea name="mbody" id="mbody"><?php echo $message['body']; ?></textarea></td></tr>
		</table>
		<input type="submit" value="Post message" name="submit">
		</form>
		<div><a href="mboard_message.php?id=<?php echo $message['id'];?>">Return to Message</a></div>
	</body>
</html>