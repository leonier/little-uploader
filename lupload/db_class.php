<?php
    header('Content-Type: text/html; charset=utf-8');
    //mysql_query("SET NAMES utf8");

	/*
	 create table uploads ( id int(6) not null auto_increment, filename varchar(256) not null, newname varchar(256) not null, date_upload timestamp  default  current_timestamp, userip varchar(128), useragent varchar(256), primary key (id)) default charset=utf8;
	create table users (id int(6) not null auto_increment, username varchar(256) not null, password_sha1 varchar(256) not null, primary key (id))default charset=utf8;
	*/
	class database
	{
        	function __construct($pdo)
        	{
         		$this->pdo = $pdo;
       		}

		function selectuploads()
		{
			$query = $this->pdo->prepare('SELECT * FROM uploads');
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		function selectuploadsid()
		{
			$query = $this->pdo->prepare('SELECT id FROM uploads order by id desc');
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		function selectuploadspage($idset, $page, $filesperpage)
		{
			$minfile=($page-1)*$filesperpage;
			$maxfile=$page*$filesperpage;
			$filecnt=count($idset);
			if($minfile<0 || $minfile>$filecnt)
				return array();
			if($filecnt-$minfile<$filesperpage&&$filecnt-$minfile>0)
				$maxfile=$minfile+$filecnt%$filesperpage;
			//echo $minfile . ',' . $maxfile;
			$sql = 'SELECT * from uploads WHERE id in (';
			for($i=$minfile; $i<$maxfile-1; $i++)
			{
				$sql = $sql . $idset[$i]['id'] . ',';
			}
			$sql = $sql . $idset[$maxfile-1]['id'] . ');';

			$query = $this->pdo->prepare($sql);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		function selectuploadfordownload($id)
		{
			$query = $this->pdo->prepare('SELECT filename, newname FROM uploads where id=:id');
			$query->execute(array(':id'=>$id));
			return $query->fetch();
		}
		function insertuploads($filename, $newname,  $ip, $ua)
		{
			$query = $this->pdo->prepare('insert into uploads (filename, newname, userip, useragent) values (:filename, :newname, :ip, :ua)');
			
			$query->execute(array(':filename' => $filename, ':newname' => $newname,  ':ip' => $ip, ':ua' => $ua));
			return $query->fetchAll();		
		}
		function deleteuploadbyid($id)
		{
			$query = $this->pdo->prepare('SELECT newname FROM uploads where id=:id');
			$query->execute(array(':id'=>$id));
			$fname=$query->fetch();
			//echo $fname[0];
			$query = $this->pdo->prepare('delete from uploads where id=:id');
			$query->execute(array(':id'=>$id));
			
			$myPathInfo = pathinfo($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']);
			$currentDir = $myPathInfo['dirname'];
			$upDir = $currentDir . '/upload/';
			unlink($upDir . $fname[0]);
		}
		function newuser($username, $password)
		{
			$password_sha1=password_hash($password, PASSWORD_DEFAULT);
			$query = $this->pdo->prepare('insert into users (username, password_sha1) values (:username, :password_sha1)');
			$query->execute(array(':username' => $username, ':password_sha1' => $password_sha1));
			return $query->fetchAll();
		}
		function getuser($username)
		{
			$query = $this->pdo->prepare('SELECT * FROM users where username=:username');
			$query->execute(array(':username' => $username));
			return $query->fetch(PDO::FETCH_ASSOC);
		}
        }