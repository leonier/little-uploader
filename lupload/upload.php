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
	echo "Not logged in, cannot upload!";
}
else
{
	$user=$db->getuser($_SESSION["username"]);
	if (is_array($user))
	{
		$ftype=$_FILES["file"]["type"];
		if ((($ftype == "image/jpeg") 
		|| ($ftype == "image/pjpeg") 
		|| ($ftype == "image/png") 
		|| ($ftype == "audio/midi") 
		|| ($ftype == "application/x-midi") 
		|| ($ftype == "audio/x-midi") 
		|| ($ftype == "audio/mid") 
		|| ($ftype == "application/x-smaf") 
		|| ($ftype == "application/msword") 
		|| ($ftype == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") 
		|| ($ftype == "image/gif") 
		|| ($ftype == "video/3gpp")
		|| ($ftype == "audio/amr")
		|| ($ftype == "application/vnd.smaf")
		|| ($ftype == "application/x-smaf")
		|| ($ftype == "") 

		))
		{
			if ($_FILES["file"]["error"] > 0)
			{
				echo "Error! Return Code: " . $_FILES["file"]["error"] . "<br />";
			}
			else
			{
				if ($_FILES["file"]["size"] > 2048000)
				{
					echo "File size too big!";
				}
				else
				{
					echo 'Uploaded filename is '. $_FILES["file"]["name"];
					echo '<br />';

					$myPathInfo = pathinfo($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']);
					$currentDir = $myPathInfo['dirname'];
					$upDir = $currentDir . '/upload/';
					$hash = md5_file($_FILES["file"]["tmp_name"]);
					$ext = end((explode(".", $_FILES["file"]["name"])));
					
					if($_FILES["file"]["size"] == 0 || !strcmp($hash, 'd41d8cd98f00b204e9800998ecf8427e'))
					{
						echo "Zero-sized files are not allowed!<br />";
						echo '<a href="index.php">Return to Index</a></body></html>';
						exit();
					}
					
					if(file_exists($upDir . $hash . '.'. $ext))
					{
						if(!strcmp($hash,md5_file($upDir . $hash . '.'. $ext)))
						{
							echo "File already exists!<br />";
							echo '<a href="index.php">Return to Index</a></body></html>';
							exit();
						}
					}

					if (move_uploaded_file($_FILES["file"]["tmp_name"], $upDir . $hash . '.'. $ext))
					{
						$db->insertuploadswithuploader($_FILES["file"]["name"],  $hash . '.'. $ext, $user['id'], gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER['HTTP_USER_AGENT']);
						echo '<br />upload Succeeded!';
						header("Location: index.php");
					}
					else
					{
						echo 'Error occurred while uploading!<br />';
					}
				}
			  }
		}
		else
		{
			echo "Invalid file ". $ftype;
		}
	}
	else
	{
		echo "Invalid user, cannot upload!";
	}
}

?>
<a href="index.php">Return to Index</a>
</body>
</html>