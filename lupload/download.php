<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);
	if (empty($_GET['id']))
	{
		echo "Request Error!";
	}
	else
	{
		$file=$db->selectuploadfordownload($_GET['id']);
		//echo "Tried to download ". $file[0] ;
		$myPathInfo = pathinfo($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']);
		$currentDir = $myPathInfo['dirname'];
		$upDir = $currentDir . '/upload/';
		$stored_filename=$upDir . $file[1] ;
		header("Content-Type: " . mime_content_type($stored_filename));
		header("Content-Length: " . filesize($stored_filename));
		header('Content-Disposition: filename="' . $file[0]. '"' );
		readfile($stored_filename);
	}
?>