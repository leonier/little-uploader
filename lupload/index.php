<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	require_once 'tools.php';
	$myfile=basename($_SERVER['PHP_SELF']);
	$db = new database($pdo);
	//$uploads = $db->selectuploads();	

	session_start();
	session_regenerate_id();

	if(!isset($_GET['uploader']) || !isset($_SESSION['username']))
	{
		$uploadID = $db->selectuploadsid();
		$upcount = count($uploadID);
	}
	else
	{
		$upuser=$db->getuserfromid($_GET['uploader']);
		if (!is_array($upuser))
		{
			$uploadID = $db->selectuploadsid();
			$upcount = count($uploadID);			
		}
		else
		{
			$myuploader = $_GET['uploader'];
			$uploadID = $db->selectuploadsidbyuploader($_GET['uploader']);
			$upcount = count($uploadID);
		}
	}

	$maxpage = ceil($upcount/$filesperpage);
	if($maxpage==0) $maxpage=1;
	if (empty($_GET['page']))
		$pagenum=1;
	else
		$pagenum=$_GET['page'];

	if(!is_numeric($pagenum))
		$pagenum=1;
	else if($pagenum<1 || $pagenum>$maxpage)
		$pagenum=1;
	
	$uploadpage=array_reverse($db->selectuploadspage($uploadID, $pagenum, $filesperpage));
	

	clearLoginReferer();
?>
<html>
	<head>
        <meta charset="UTF-8">
		<title>MiniUploader</title>
	<style>
		table, th, td {
   		border: 1px solid black;
		}
	</style>
	</head>
	<body>
		<div>
		<?php if(!isset($_SESSION['username'])): ?>
		<?php $islogin=0; ?>
		<p>You must <a href="loginpage.php">Login</a> to upload files.</p>
		<p><a href="register.php">Register</a> new user</p>
		<?php else: ?>
		<?php
			$curuser=$db->getuser($_SESSION['username']);
		?>
		<p>Logged in as <a href="usrinfo.php?uid=<?php echo $curuser['id']; ?>"><?php echo $_SESSION['username']; ?></a> <a href="logout.php">Logout</a></p>
		<?php $islogin=1; ?>
		<form action="upload.php" method="post" enctype="multipart/form-data">
		Select file to upload:
		<input type="file" name="file" id="file">
		<input type="submit" value="Upload" name="submit">
		</form>
		<?php endif; ?>
		</div>

		<div>
		<form action="<?php echo $myfile;?>" method="get">
		<?php if(!isset($myuploader)): ?>
		<?php echo $upcount; ?> files, page <input type="text" name="page" size="3" value="<?php echo $pagenum; ?>">/<?php echo $maxpage; ?> 
		<?php else: ?>
		User <?php echo $upuser['username']; ?> uploaded <?php echo $upcount; ?> files, page <input type="text" name="page" size="3" value="<?php echo $pagenum; ?>">/<?php echo $maxpage; ?> 		
		<input type="hidden" name="uploader" value="<?php echo $myuploader; ?>" >
		<?php endif; ?>
		<input type="submit" value="Go">
		<?php if(isset($myuploader)): ?>
		<a href="<?php echo $myfile;?>">All files</a>
		<?php endif;?>
		</form> 		

		</div>
		<div>

		<?php
			if(isset($myuploader))
			{
				$mytrail= "&uploader=" . $myuploader;
			}
		?>
		<a href="<?php echo $myfile; ?>?page=1<?php echo $mytrail; ?>">&lt;&lt;</a>

		<?php if($pagenum>1):?>
		<a href="<?php echo $myfile; ?>?page=<?php echo $pagenum-1;?><?php echo $mytrail; ?>"><?php echo $pagenum-1;?></a>
		<?php endif; ?>

		<?php echo $pagenum;?>

		<?php if($pagenum<$maxpage):?>
		<a href="<?php echo $myfile; ?>?page=<?php echo $pagenum+1;?><?php echo $mytrail; ?>"><?php echo $pagenum+1;?></a>
		<?php endif; ?>

		<a href="<?php echo $myfile; ?>?page=<?php echo $maxpage;?><?php echo $mytrail; ?>">&gt;&gt;</a>

		</div>

		<table>
			<tr>
				<td>Filename</td>
				<?php if(!isset($myuploader)): ?>
				<td>Uploader</td>
				<?php endif; ?>
				<td>Upload date</td>
				<td>Operation</td>
			</tr>
			
			<?php foreach($uploadpage as $upfile): ?>
			<tr>
				<td><a href="download.php?id=<?php echo $upfile['id'];?>"><?php echo $upfile['filename']; ?></a></td>
				<?php if(!isset($myuploader)): ?>
				<td>
					<?php if(isset($upfile['uploader'])): ?>
					<?php
						$uploader=$db->getuserfromid($upfile['uploader']);
						if($islogin==1)
						{
							echo "<a href=\"usrinfo.php?uid=" . $uploader['id'] . "\">" . $uploader['username'] . "</a>";	
						}
						else
						{
							echo $uploader['username'];
						}
					?>
					<?php else: ?>
					Anonymous
					<?php endif; ?>
				</td>
				<?php endif; ?>

				<td><?php echo $upfile['date_upload']; ?></td>
				<td>
					<?php if($islogin==1): ?>
					<?php if(isset($upfile['uploader'])): ?>
					<?php if($uploader['username'] == $_SESSION['username']): ?>
					<a href="delfile.php?id=<?php echo $upfile['id'];?>">Delete</a>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>

		<div>
		<a href="<?php echo $myfile;?>?page=1<?php echo $mytrail; ?>">&lt;&lt;</a>

		<?php if($pagenum>1):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum-1;?><?php echo $mytrail; ?>"><?php echo $pagenum-1;?></a>
		<?php endif; ?>

		<?php echo $pagenum;?>

		<?php if($pagenum<$maxpage):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum+1;?><?php echo $mytrail; ?>"><?php echo $pagenum+1;?></a>
		<?php endif; ?>

		<a href="<?php echo $myfile;?>?page=<?php echo $maxpage;?><?php echo $mytrail; ?>">&gt;&gt;</a>

		</div>
		<div><a href="mboard_index.php">Message Board</a></div>
	</body>
</html>