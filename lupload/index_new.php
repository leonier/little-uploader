<?php 
	header('Content-Type: text/html; charset=utf-8');
	require_once 'db_config.php';
	require_once 'db_class.php';
	$db = new database($pdo);
	$uploads = $db->selectuploads();

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
				<td>
					<?php if($islogin==1): ?>
					<a href="delfile.php?id=<?php echo $upfile['id'];?>">Delete</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>


	</body>
</html>