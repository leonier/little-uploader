<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);
	$uploads = $db->selectuploads();

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
		<form action="upload.php" method="post" enctype="multipart/form-data">
		Select file to upload:
		<input type="file" name="file" id="file">
		<input type="submit" value="Upload" name="submit">
		</form>
		</div>

		<table>
			<tr>
				<td>Filename</td>
				<td>Upload date</td>
				<td>Operation</td>
			</tr>
			
			<?php foreach($uploads as $upfile): ?>
			<tr>
				<td><a href="download.php?id=<?php echo $upfile['id'];?>"><?php echo $upfile['filename']; ?></a></td>
				<td><?php echo $upfile['date_upload']; ?></td>
				<td><a href="delfile.php?id=<?php echo $upfile['id'];?>">Delete</a></td>
			</tr>
			<?php endforeach; ?>
		</table>


	</body>
</html>