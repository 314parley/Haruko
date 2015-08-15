<?php

###################################################

#		Functions for checking the server         #

#				by	parley						  #

###################################################



function site(){

	$switch = 1;

	if(!$switch){

		header('HTTP/1.1 503 Service Temporarily Unavailable');

	}else{

		header("HTTP/1.1 314 I like pi");

	}

}





function db(){

	

	$mysqli_connection = new MySQLi('localhost', 'root', 'qfjy6fOsbb66nPCs', 'haruko', '/media/dmk/specter/private/mysql/socket');

		if ($mysqli_connection->connect_error) {

			$switch = 0;

		}

		else {

			$switch = 1;

		}

	#$switch = 1;

	if(!$switch){

		header('HTTP/1.1 503 Service Temporarily Unavailable');

	}else{

		header("HTTP/1.1 314 I like pi");

	}

}



####################################################

#      DONT FUCKING TOUCH THIS I SWEAR TO FUCK     #

####################################################



$do = $_GET['do'];

switch($do) 

{



case "db": 

db();

break;



#case "page2": 

#@include('page2.php'); 

#break;



#case "page3": 

#@include('page3.php'); 

#break;



default:

site();

break;

} 

?> 