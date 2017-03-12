<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);
?>
<html>
	<head>
        <meta charset="UTF-8">
		<title>MiniUploader</title>
	</head>
	<body>
	<?php 
	//echo $_GET['id'];
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
		if(isset($file['uploader']))
		{
			echo "Cannot delete non-anonymous file in anonymous mode!";
			exit();
		}
		$db->deleteuploadbyid($_GET['id']);
		header("Location: index_old.php"); 
		exit();
	}	
	?>

	</body>
</html>