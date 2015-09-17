<?php
	#header('content-type: application/json; charset=utf-8');
	include("config.php");
    include("inc/mitsuba.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);
    $haruko = new Mitsuba($conn);
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
	        $row["forced_anonymous"] = $row["noname"];
	        unset($row["noname"]);
	        $row["default_name"] = $row["anonymous"];
	        unset($row["anonymous"]);
			$row["board_type"] = $row["type"];
	        unset($row["type"]);
	        //cooldown timers [define what `cooldowns` is here
	        $row["cooldowns"] = $row["cooldowns"];
	        	//cooldowns between threads
	        	$row["cooldowns"]["threads"] = $row["time_between_threads"];
				unset($row["time_between_threads"]);
				//cooldowns between posts
				$row["cooldowns"]["posts"] = $row["time_between_posts"];
				unset($row["time_between_posts"]);
				$row["cooldowns"]["delete"] = $row["time_to_delete"];
				unset($row["time_between_posts"]);
				
	        $row["meta_description"] = $row["des"];
	        unset($row["des"]);
	        $row["max_filesize"] = $row["filesize"];
	        unset($row["filesize"]);


	        $boardArr[] = $row;
	    }
	}
	$finalJSON = json_encode($boardArr,JSON_NUMERIC_CHECK);
	
	file_put_contents("boards.json",$finalJSON);