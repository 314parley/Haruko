<?php
	/*
	vote.php has yet to be written.
	for now, it's just collecting info and stuff.	
		
	*/
	
	include("config.php");
	include("inc/mitsuba.php");
	$conn = new mysqli($db_host, $db_username, $db_password, $db_database);
	$haruko = new Mitsuba($conn);
	//$sql = "SELECT * FROM `posts` WHERE `strip` != ''";
	$sql = "SELECT DISTINCT strip from `posts`";
	//$trip = $conn->query("SELECT * FROM posts WHERE trip IS NOT NULL");
	if(!$result = $conn->query($sql)){
    	die('There was an error running the query [' . $conn->error . ']');
    }else{
	    while($row = $result->fetch_assoc()){
		    echo("<pre>");
		    /* RIP INSECURE TRIPCODES */
		    /*if($row['trip']){
		    echo "Tripcode: ".$row['trip']. "&nbsp;<br />";
		    }*/
		    if($row['strip']){
		    echo "Secure Tripcode: ".$row['strip'];
		    }
		    echo("</pre>");
	}
    }
?>