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

	if(!isset($_SESSION['username']))
	{
		echo "Not logged in, cannot delete replies!";
		exit();
	}
	$curuser=$db->getuser($_SESSION["username"]);
	if (!is_array($curuser))
	{
		echo "Not logged in, cannot delete replies!";
		exit();		
	}
	
	if (empty($_GET['id']))
	{
		echo "Request Error!";
	}
	else
	{
		$reply=$db->selectreplybyid($_GET['id']);
		if(!isset($reply['id']))
		{
			echo "Invalid reply!";
			exit();
		}
		if(strcmp($reply['poster'], $curuser['id'])!=0)
		{
			echo "Cannot delete reply of other users!";
			exit();
		}
		$db->deletereplybyid($_GET['id']);
		header("Location: mboard_message.php?id=" . $reply['message']); 
		exit();
	}	
	?>

	</body>
</html>