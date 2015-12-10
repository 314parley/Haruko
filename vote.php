<?php
/*
    vote.php has yet to be written.
    for now, it's just collecting info and stuff.   
*/
	include("config.php");
	include("inc/mitsuba.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);
    $haruko = new Mitsuba($conn);
	$sql = "SELECT * FROM `posts` WHERE `strip` != ''";
	$sql = "SELECT DISTINCT strip from `posts`";
	$trip = $conn->query("SELECT * FROM posts WHERE trip IS NOT NULL");
	
	if(!$result = $conn->query($sql)){

        die('There was an error running the query [' . $conn->error . ']');

    }else{

        while($row = $result->fetch_assoc()){

            echo("<pre>");

            if($row['strip']){

            echo "Secure Tripcode: ".$row['strip'];

            }

            echo("</pre>");

    }

    }

?>

<!doctype html>



<html lang="en">

<head>

  <meta charset="utf-8">



  <title>314chan | Vote</title>

  <meta name="description" content="Vote on your staff!">

  <meta name="author" content="314telecommunications">



  <link rel="stylesheet" href="css/styles.css?v=1.0">



  <!--[if lt IE 9]>

  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

  <![endif]-->

</head>



<body>

	Vote!

  <script src="js/scripts.js"></script>

</body>

</html>