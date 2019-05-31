<?php
	$connection = new mysqli("localhost","meru_nur","","humidex");
	if(!$connection->connect_error){

		define("CONNECTED",true);

	}else{

		define("CONNECTED",false);

	}

?>
