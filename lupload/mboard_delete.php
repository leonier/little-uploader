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
		<title>MiniUploader</title>
	</head>
	<body>
	<?php 
	//echo $_GET['id'];

	if(!isset($_SESSION['username']))
	{
		echo "Not logged in, cannot delete messages!";
		exit();
	}
	$curuser=$db->getuser($_SESSION["username"]);
	if (!is_array($curuser))
	{
		echo "Not logged in, cannot delete messages!";
		exit();		
	}
	
	if (empty($_GET['id']))
	{
		echo "Request Error!";
	}
	else
	{
		$message=$db->selectmessagebyid($_GET['id']);
		if(!isset($message['id']))
		{
			echo "Invalid message!";
			exit();
		}
		if(strcmp($message['poster'], $curuser['id'])!=0)
		{
			echo "Cannot delete message of other users!";
			exit();
		}
		$db->deletemessagebyid($_GET['id']);
		header("Location: mboard_index.php"); 
		exit();
	}	
	?>

	</body>
</html>