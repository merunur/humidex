<?php

if($_SERVER['REQUEST_METHOD']=='GET'){
    include 'db.php';
    
    if(CONNECTED){
        
    $searchdate = $_GET['searchDate'];
    //$mysqlFormat = date('Y-m-d H:i:s', strtotime($_GET['searchDate']));
    
   $query = $connection->query("SELECT * FROM humidex WHERE timestamp LIKE \"%".$searchdate."%\" ORDER BY id ASC");
   //$jsonArray = array(); 
     $data= array();
    while ($row = $query->fetch_object()) { 
     $data[] = $row;
    //  $jsonArrayItem = array();
		   // $jsonArrayItem['label'] = $row->timestamp;
		   // $jsonArrayItem['value'] = $row-temperature;
		    // array_push($jsonArray, $jsonArrayItem);
  
    }
    $query->close();
    $connection->close();
 		//set the response content type as JSON
	 //	header('Content-type: application/json');
	 	//output the return value of json encode using the echo function. 
 	 print json_encode($data);
  	//	echo json_encode($jsonArray);
    }
}
?>

