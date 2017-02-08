<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$myfile=basename($_SERVER['PHP_SELF']);
	$db = new database($pdo);
	//$uploads = $db->selectuploads();
	$uploadID = $db->selectuploadsid();
	$upcount = count($uploadID);
	$maxpage = ceil($upcount/$filesperpage);
	if($maxpage==0) $maxpage=1;
	if (empty($_GET['page']))
		$pagenum=1;
	else
		$pagenum=$_GET['page'];
	$uploadpage=array_reverse($db->selectuploadspage($uploadID, $pagenum, $filesperpage));
	
	session_start();
	session_regenerate_id();
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
		<p>You must <a href="loginpage.php">Login</a> to upload files.</p>
		<p><a href="register.php">Register</a> new user</p>
		<?php else: ?>
		<p>Logged in as <?php echo $_SESSION['username']; ?> <a href="logout.php">Logout</a></p>
		<?php $islogin=1; ?>
		<form action="upload.php" method="post" enctype="multipart/form-data">
		Select file to upload:
		<input type="file" name="file" id="file">
		<input type="submit" value="Upload" name="submit">
		</form>
		<?php endif; ?>
		</div>

		<div>
		<?php echo $upcount; ?> files, page <?php echo $pagenum; ?>/<?php echo $maxpage; ?> 
		</div>
		<div>
		<a href="<?php echo $myfile;?>?page=1">&lt;&lt;</a>

		<?php if($pagenum>1):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum-1;?>"><?php echo $pagenum-1;?></a>
		<?php endif; ?>

		<?php echo $pagenum;?>

		<?php if($pagenum<$maxpage):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum+1;?>"><?php echo $pagenum+1;?></a>
		<?php endif; ?>

		<a href="<?php echo $myfile;?>?page=<?php echo $maxpage;?>">&gt;&gt;</a>

		</div>

		<table>
			<tr>
				<td>Filename</td>
				<td>Upload date</td>
				<td>Operation</td>
			</tr>
			
			<?php foreach($uploadpage as $upfile): ?>
			<tr>
				<td><a href="download.php?id=<?php echo $upfile['id'];?>"><?php echo $upfile['filename']; ?></a></td>
				<td><?php echo $upfile['date_upload']; ?></td>
				<td>
					<?php if($islogin==1): ?>
					<a href="delfile.php?id=<?php echo $upfile['id'];?>">Delete</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>

		<div>
		<a href="<?php echo $myfile;?>?page=1">&lt;&lt;</a>

		<?php if($pagenum>1):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum-1;?>"><?php echo $pagenum-1;?></a>
		<?php endif; ?>

		<?php echo $pagenum;?>

		<?php if($pagenum<$maxpage):?>
		<a href="<?php echo $myfile;?>?page=<?php echo $pagenum+1;?>"><?php echo $pagenum+1;?></a>
		<?php endif; ?>

		<a href="<?php echo $myfile;?>?page=<?php echo $maxpage;?>">&gt;&gt;</a>

		</div>

	</body>
</html>