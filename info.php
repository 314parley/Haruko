<?php
/*
    vote.php has yet to be written.
    for now, it's just collecting info and stuff.   
*/
	include("config.php");
	include("inc/mitsuba.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);
    $haruko = new Mitsuba($conn);
    $thread_id = "64";
    $sql = "SELECT * FROM posts WHERE board='314' AND resto=64";
	if(!$result = $conn->query($sql)){

        die('There was an error running the query [' . $conn->error . ']');

    }else{

        while($row2 = $result->fetch_assoc()){

            echo("<pre>");
            if($row2["ip"]){
	            unset($row2["ip"]);
            }
            if($row2["password"]){
	            unset($row2["password"]);
            }
            if($row2['resto'] == "0"){
	            $row2['resto'] = $row2['id'];
            }
            echo json_encode($row2);
            echo("</pre>");

    }

    }
?>