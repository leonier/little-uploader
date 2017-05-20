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
		function selectuploadbyid($id)
		{
			$query = $this->pdo->prepare('SELECT * FROM uploads where id=:id');
			$query->execute(array(':id'=>$id));
			return $query->fetch(PDO::FETCH_ASSOC);
		}
		function insertuploads($filename, $newname,  $ip, $ua)
		{
			$query = $this->pdo->prepare('insert into uploads (filename, newname, userip, useragent) values (:filename, :newname, :ip, :ua)');
			
			$query->execute(array(':filename' => $filename, ':newname' => $newname,  ':ip' => $ip, ':ua' => $ua));
			return $query->fetchAll();		
		}
		function insertuploadswithuploader($filename, $newname, $uploader,  $ip, $ua)
		{
			$query = $this->pdo->prepare('insert into uploads (filename, newname, uploader, userip, useragent) values (:filename, :newname, :uploader, :ip, :ua)');
			
			$query->execute(array(':filename' => $filename, ':newname' => $newname, ':uploader' => $uploader,  ':ip' => $ip, ':ua' => $ua));
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
		function getuserfromid($id)
		{
			$query = $this->pdo->prepare('SELECT * FROM users where id=:id');
			$query->execute(array(':id' => $id));
			return $query->fetch(PDO::FETCH_ASSOC);			
		}
		function selectmessagesid()
		{
			$query = $this->pdo->prepare('SELECT id FROM messages order by id desc');
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		function selectmessagespage($idset, $page, $filesperpage)
		{
			$minfile=($page-1)*$filesperpage;
			$maxfile=$page*$filesperpage;
			$filecnt=count($idset);
			if($minfile<0 || $minfile>$filecnt)
				return array();
			if($filecnt-$minfile<$filesperpage&&$filecnt-$minfile>0)
				$maxfile=$minfile+$filecnt%$filesperpage;


			//echo $minfile . ',' . $maxfile;
			$sql = 'SELECT id,poster,title,date_create from messages WHERE id in (';
			for($i=$minfile; $i<$maxfile-1; $i++)
			{
				$sql = $sql . $idset[$i]['id'] . ',';
			}
			$sql = $sql . $idset[$maxfile-1]['id'] . ');';

			$query = $this->pdo->prepare($sql);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		function insertmessages($poster, $title, $mbody,  $ip, $ua)
		{
			$query = $this->pdo->prepare('insert into messages (poster, title, body, userip, useragent) values (:poster, :title, :mbody, :ip, :ua)');
			 
			$query->execute(array(':poster' => $poster, ':title' => $title,  ':mbody' => $mbody, ':ip' => $ip, ':ua' => $ua));
			return $query->fetchAll();		
		}
		function selectmessagebyid($id)
		{
			$query = $this->pdo->prepare('SELECT * FROM messages where id=:id');
			$query->execute(array(':id'=>$id));
			return $query->fetch(PDO::FETCH_ASSOC);
		}
		function deletemessagebyid($id)
		{
			$query = $this->pdo->prepare('delete from messages where id=:id');
			$query->execute(array(':id'=>$id));
		}
		function updatemessages($id, $title, $mbody,  $ip, $ua)
		{
			$query = $this->pdo->prepare("update messages set title=:title, body=:mbody, userip=:ip, useragent=:ua, date_modify=:dm where id=:id");
			 
			$query->execute(array(':id' => $id, ':title' => $title,  ':mbody' => $mbody, ':ip' => $ip, ':ua' => $ua, ':dm' => date("Y-m-d H:i:s") ));
			return $query->fetchAll();		
		}
		function selectreplies($message)
		{
			$query = $this->pdo->prepare('SELECT * FROM replies where message=:message order by id');
			$query->execute(array(':message'=>$message));
			return $query->fetchAll(PDO::FETCH_ASSOC);			
		}
		function insertreplies($poster, $message, $title, $mbody,  $ip, $ua)
		{
			$query = $this->pdo->prepare('insert into replies (poster, message, title, body, userip, useragent) values (:poster, :message, :title, :mbody, :ip, :ua)');
			 
			$query->execute(array(':poster' => $poster, ':message' => $message, ':title' => $title,  ':mbody' => $mbody, ':ip' => $ip, ':ua' => $ua));
			return $query->fetchAll();		
		}
		function selectreplybyid($id)
		{
			$query = $this->pdo->prepare('SELECT * FROM replies where id=:id');
			$query->execute(array(':id'=>$id));
			return $query->fetch(PDO::FETCH_ASSOC);
		}
		function deletereplybyid($id)
		{
			$query = $this->pdo->prepare('delete from replies where id=:id');
			$query->execute(array(':id'=>$id));
		}
		function updatereplies($id, $title, $mbody,  $ip, $ua)
		{
			$query = $this->pdo->prepare("update replies set title=:title, body=:mbody, userip=:ip, useragent=:ua, date_modify=:dm where id=:id");
			 
			$query->execute(array(':id' => $id, ':title' => $title,  ':mbody' => $mbody, ':ip' => $ip, ':ua' => $ua, ':dm' => date("Y-m-d H:i:s") ));
			return $query->fetchAll();		
		}
		function countuseruploads($id)
		{
			$query = $this->pdo->prepare("select count(*) from uploads where uploader=:id");
			$query->execute(array(':id'=>$id));
			$data=$query->fetchColumn();
			return $data;
		}
		function countusermessages($id)
		{
			$query = $this->pdo->prepare("select count(*) from messages where poster=:id");
			$query->execute(array(':id'=>$id));
			$data=$query->fetchColumn();
			return $data;
		}
		function countuserreplies($id)
		{
			$query = $this->pdo->prepare("select count(*) from replies where poster=:id");
			$query->execute(array(':id'=>$id));
			$data=$query->fetchColumn();
			return $data;
		}
		function updateuserpassword($id, $password)
		{
			$password_sha1=password_hash($password, PASSWORD_DEFAULT);
			$query = $this->pdo->prepare("update users set password_sha1=:password_sha1 where id=:id");
			$query->execute(array(':id'=>$id, ':password_sha1'=>$password_sha1 ));
			return $password_sha1;
		} 
        }