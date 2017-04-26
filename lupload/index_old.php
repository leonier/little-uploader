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

	if(!is_numeric($pagenum))
		$pagenum=1;
	else if($pagenum<1 || $pagenum>$maxpage)
		$pagenum=1;

	$uploadpage=array_reverse($db->selectuploadspage($uploadID, $pagenum, $filesperpage));
		
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
		<form action="upload_old.php" method="post" enctype="multipart/form-data">
		Select file to upload:
		<input type="file" name="file" id="file">
		<input type="submit" value="Upload" name="submit">
		</form>
		</div>
		<div>
		<form action="<?php echo $myfile;?>" method="get">
		<?php echo $upcount; ?> files, page <input type="text" name="page" size="3" value="<?php echo $pagenum; ?>">/<?php echo $maxpage; ?>
		<input type="submit" value="Go">
		</form> 
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
				<td><a href="delfile_old.php?id=<?php echo $upfile['id'];?>">Delete</a></td>
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