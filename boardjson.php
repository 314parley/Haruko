<?php
	//include required files
	include("config.php");
    include("inc/mitsuba.php");
    
    //connect to MySQLi
    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);
    $haruko = new Mitsuba($conn);
    
    //get all boards
    $sql = "SELECT * FROM `boards`";
    
    if(!$result = $conn->query($sql)){
        die('There was an error running the query [' . $conn->error . ']');
    }else{
        while($row = $result->fetch_assoc()){
	        //changing various rows because Vichan's format is a bit more widly used. also reorganizing them, because fuck PHP.
	        $row["uri"] = $row["short"];
	        unset($row["short"]);
	        $row["title"] = $row["name"];
	        unset($row["name"]);
	        $row["subtitle"] = $row["message"];
	        unset($row["message"]);
	        $row["board_type"] = $row["type"];
	        unset($row["type"]);
	        $row["default_name"] = $row["anonymous"];
	        unset($row["anonymous"]);
	        $row[""] = $row[""]
	        

	        $boardArr[] = $row;
	    }
	}
	$finalJSON = json_encode($boardArr);
	
	file_put_contents("boards.json",$finalJSON);