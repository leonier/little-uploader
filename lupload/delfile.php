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
		$db->deleteuploadbyid($_GET['id']);
		header("Location: index.php"); 
		exit();
	}	
	?>

	</body>
</html>