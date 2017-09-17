<?php

function processURL($string)
{
	//courtesy of http://stackoverflow.com/questions/4390556/extract-url-from-string
	$string = preg_replace('/https?:\/\/[^\s"<>]+/','<a href="$0" target="_blank">$0</a>',$string);
	return $string;
}

function clearLoginReferer()
{
	if(isset($_SESSION['login_referer']))
		unset($_SESSION['login_referer']);
}
function lastPage()
{
	echo "<p><a href=\"" . $_SERVER['HTTP_REFERER'] . "\">Return to last page</a></p>";
}
function indexPage()
{
	echo "<p><a href=\"index.php\">Return to index</a></p>";
}
function userInfoPage($uid)
{
	echo "<p><a href=\"usrinfo.php?uid=" . $uid . "\">Return to user info page</a></p>";
}
function lastPageorindexPage()
{
	if(isset($_SERVER['HTTP_REFERER']))
		lastPage();
	else
		indexPage();
}
function isPasswordSane($password)
{
	if (strlen($password) < 6) 
		return 0;
    	/*
	if (!preg_match("#[0-9]+#", $pwd))
		return 0;
	if (!preg_match("#[a-zA-Z]+#", $pwd))
		return 0;
	*/
	//Pswd requirement must not be too hard for feature phone keyboard...

	return 1;
}
function isValidMIMEType($ftype)
{
	if ((($ftype == "image/jpeg") 
	|| ($ftype == "image/pjpeg") 
	|| ($ftype == "image/jpg") 
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
	|| ($ftype == "audio/x-amr")
	|| ($ftype == "application/vnd.smaf")
	|| ($ftype == "application/x-smaf")
	|| ($ftype == "") 
	))
		return 1;
	else
		return 0;
}
//Filter out potentially dangerous filename extension.
function isExecutableFile($filename)
{
	$lfilename=strtolower($filename);
	if(strstr($lfilename,'.php')
	|| strstr($lfilename,'.sh')
	|| strstr($lfilename,'.py')
	|| strstr($lfilename,'.pl')
	|| strstr($lfilename,'.rb')
	)
		return 1;
	else
		return 0;
}
?>