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
		echo "Not logged in, cannot post message!";
		exit();
	}
	$curuser=$db->getuser($_SESSION["username"]);
	if (!is_array($curuser))
	{
		echo "Not logged in, cannot post message!";
		exit();		
	}
	
	if (empty($_POST['mbody']))
	{
		echo "Request Error!";
	}
	else
	{
		
		$poster=$curuser['id'];
		$uip=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$db->insertmessages($poster, $_POST['title'],$_POST['mbody'],$uip,$_SERVER['HTTP_USER_AGENT']);
		header("Location: mboard_index.php"); 
		exit();
	}	
	?>

	</body>
</html>