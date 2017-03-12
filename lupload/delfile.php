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
		echo "Not logged in, cannot delete files!";
		exit();
	}
	$curuser=$db->getuser($_SESSION["username"]);
	if (!is_array($curuser))
	{
		echo "Not logged in, cannot delete files!";
		exit();		
	}
	
	if (empty($_GET['id']))
	{
		echo "Request Error!";
	}
	else
	{
		$file=$db->selectuploadbyid($_GET['id']);
		if(!isset($file['filename']))
		{
			echo "Invalid file!";
			exit();
		}
		if(strcmp($file['uploader'], $curuser['id'])!=0)
		{
			echo "Cannot delete file of other users!";
			exit();
		}
		$db->deleteuploadbyid($_GET['id']);
		header("Location: index.php"); 
		exit();
	}	
	?>

	</body>
</html>