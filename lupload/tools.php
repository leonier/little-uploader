<?php

function processURL($string)
{
	//courtesy of http://stackoverflow.com/questions/4390556/extract-url-from-string
	$string = preg_replace('/https?:\/\/[^\s"<>]+/','<a href="$0" target="_blank">$0</a>',$string);
	return $string;
}

?>