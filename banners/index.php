<html lang="en">

<head>

	<title>314chan Banners</title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<link rel="shortcut icon" href="/favicon.ico" />

	<link rel="stylesheet" type="text/css" href="/css/img_globals.css">

	<link rel="stylesheet" type="text/css" href="/css/front.css" />

	<link rel="stylesheet" type="text/css" href="/css/site_front.css" />

	<link rel="stylesheet" type="text/css" href="/css/site_global.css">

	<link rel="stylesheet" type="text/css" href="/css/futaba.css" title="futaba">

	<style type="text/css">

img {

  -moz-user-select: none;

  -webkit-user-select: none;

  /* this will work for QtWebKit in future */

  -webkit-user-drag: none;

}

</style>

</head>

<body>

<h1 align=center>Upload a banner for review:</h1><br />

<center>

<form action="upload.php" method="post"

enctype="multipart/form-data">

<label for="file" class="postblock" style="padding: 5px 50px 5px 50px; align:left;">File:</label>

<input type="file" name="file" id="file"><br>

<input type="submit" name="submit" value="Submit">

</form>

</center>

<h1 align=center>Banners currently in rotation:</h1><br />

<?php

error_reporting();

$files = glob("*.{png,jpg,jpeg,gif}", GLOB_BRACE);

// $files = glob("*.gif");

for ($i = 1;$i < count($files);$i++) {

    $num = $files[$i];

    echo '<img src="' . $num . '" alt="image" id="img" style="border:1px solid black">' . "&nbsp;&nbsp;";

}

?>

<br><br>

<SCRIPT LANGUAGE="JavaScript">

var message="Function Disabled!";

///////////////////////////////////

function clickIE() {if (document.all) {return false;}}

function clickNS(e) {if 

(document.layers||(document.getElementById&&!document.all)) {

if (e.which==2||e.which==3) {return false;}}}

if (document.layers) 

{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}

else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}



document.oncontextmenu=new Function("return false");

// --> 

</script>

</body>