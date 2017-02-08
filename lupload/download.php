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
		$mimetype=mime_content_type($stored_filename);

		//.amr is not defined in magic.mime
		$ext = strtolower(pathinfo($stored_filename, PATHINFO_EXTENSION));
		if(!strcmp($ext,'amr')) 
			$mimetype='audio/amr';
		if(!strcmp($ext,'mmf')||!strcmp($ext,'smaf')) 
			$mimetype='application/x-smaf';
		

		header("Content-Type: " . $mimetype);
		header("Content-Length: " . filesize($stored_filename));

		if(!strcmp($mimetype, 'image/jpeg') ||
		!strcmp($mimetype, 'image/png') ||
		!strcmp($mimetype, 'image/gif')  )
		{
			header('Content-Disposition: filename="' . $file[0]. '"' );
		}
		else
		{
			header('Content-Disposition: attachment; filename="' . $file[0]. '"' );
		}

		readfile($stored_filename);
	}
?>