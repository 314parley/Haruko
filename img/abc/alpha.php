<?php
PHP_SAPI === 'cli' or die('not allowed');
for ($i=65; $i<=90; $i++) {
$let = chr($i);
$leturl = $_SERVER['DOCUMENT_ROOT'].$let.".gif";
$sevenURL = "http://7chan.org/res/ani".$let.".gif";
if(!@copy($sevenURL,$leturl))
{
	$errors= error_get_last();
	echo "COPY ERROR: ".$errors['type'];
	echo "\n".$errors['message'];
} else {
	echo "File copied from remote!\n";
}
}
?>