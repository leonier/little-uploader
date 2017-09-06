<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);

	if (empty($_POST['id']))
	{
		echo "Request Error!";
	}

	$messageID = $_POST['id'];
	$message=$db->selectmessagebyid($messageID);

	if (!is_array($message))
	{
		echo "Request Error!";
		exit();
	}
	$poster=$db->getuserfromid($message['poster']);

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
		echo "Not logged in, cannot edit message!";
		exit();
	}
	$curuser=$db->getuser($_SESSION["username"]);
	if (!is_array($curuser))
	{
		echo "Not logged in, cannot edit message!";
		exit();		
	}
	if(strcmp($poster['username'], $curuser['username'])!=0)
	{
		echo "Cannot edit message of others!";
		exit();		
	}	
	

	if (empty($_POST['title']) || empty($_POST['mbody']))
	{
		echo "Request Error!";
	}
	else
	{
		
		$poster=$curuser['id'];
		$uip=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$db->updatemessages($messageID, $_POST['title'],$_POST['mbody'],$uip,$_SERVER['HTTP_USER_AGENT']);
		
		header("Location: mboard_message.php?id=" . $messageID); 

		exit();
	}	
	?>

	</body>
</html>