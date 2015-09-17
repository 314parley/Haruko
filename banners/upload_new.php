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

<?php

$allowedExts = array("jpg", "jpeg", "gif", "png");

$extension = end(explode(".", $_FILES["file"]["name"]));

// Image size

$test = getimagesize($_FILES["file"]["tmp_name"]);

$width = $test[0];

$height = $test[1];

// end image size

if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && in_array($extension, $allowedExts)) {

    if ($_FILES["file"]["error"] > 0) {

        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        die();

    } else {

        if (file_exists("upload/" . $_FILES["file"]["name"]) || file_exists($_FILES["file"]["name"])) {

            echo $_FILES["file"]["name"] . " already exists. ";

        } else if ($width > 300 || $height > 100) {

            echo '<h1>This image is larger than 300x100, right? Fix it.</h1>';

            die();

        } else {

            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);

            echo "<h1>Thanks! '" . $_FILES["file"]["name"] . "' has been submitted for review.</h1>";

        }

    }

} else {

    echo "Invalid file";

}

?>