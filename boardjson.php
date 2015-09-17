<?php

	include("config.php");

    include("inc/mitsuba.php");

    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);

    $haruko = new Mitsuba($conn);

    

    $sql = "SELECT * FROM `boards`";

    

    if(!$result = $conn->query($sql)){

        die('There was an error running the query [' . $conn->error . ']');

    }else{

        while($row = $result->fetch_assoc()){

/*            echo("<pre>");

            if($row['strip']){

            echo "Secure Tripcode: ".$row['strip'];

            }

            echo("</pre>");

    }*/

    echo("<pre>");

    //var_dump($row);

    echo json_encode($row);

    echo("</pre>");

    }

    }