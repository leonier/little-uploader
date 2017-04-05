<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);

	if (empty($_POST['id']))
	{
		echo "Request Error!";
	}

	$replyID = $_POST['id'];
	$reply=$db->selectreplybyid($replyID);

	if (!is_array($reply))
	{
		echo "Request Error!";
		exit();
	}
	$replier=$db->getuserfromid($reply['poster']);

	session_start();
	session_regenerate_id();
?>
<html>
	<head>
        <meta charset="UTF-8">
		<title>MiniUploader</title>
	</head>
	<body>
	<?php 
	//echo $_GET['id'];

	if(!isset($_SESSION['username']))
	{
		echo "Not logged in, cannot edit reply!";
		exit();
	}
	$curuser=$db->getuser($_SESSION["username"]);
	if (!is_array($curuser))
	{
		echo "Not logged in, cannot edit reply!";
		exit();		
	}
	if(strcmp($replier['username'], $curuser['username'])!=0)
	{
		echo $replier['username'] . ",". $curuser['username'];
		echo "Cannot edit reply of others!";
		exit();		
	}	
	

	if (empty($_POST['mbody']))
	{
		echo "Request Error!";
	}
	else
	{
		$uip=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$db->updatereplies($replyID, $_POST['title'],$_POST['mbody'],$uip,$_SERVER['HTTP_USER_AGENT']);
		header("Location: mboard_message.php?id=" . $reply['message']); 
		exit();
	}	
	?>

	</body>
</html>