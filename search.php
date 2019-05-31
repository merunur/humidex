<!DOCTYPE html>
<html>
<head>
<style>  
* {
    box-sizing: border-box;
}

body {
    font-family: Arial;
    padding: 1.5%;
    background: #f1f1f1;
}

</style>
<link rel="stylesheet" href="styles.css" type="text/css" />
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>

     <div class="searchcard"> 
     <p class="chooserange"> Choose date range to see history </p> 
     <form name="f1" method="post" action="search.php">
      <input type="date" class="inputdate"  id="startDate" name="startDate" placeholder="start date">
      <input type="date" class="inputdate" id="endDate" name="endDate" placeholder="end date">
      <input type="submit" class="searchbutton" value="Search"/></br>
     </form>
   
</div>
<?php


  include 'db.php';
mysql_select_db("humidex");
if (isset($_POST['startDate'])) { $data1 = $_POST['startDate'];}
if (isset($_POST['endDate'])) { $data2 = $_POST['endDate'];}

$result = $connection->query("SELECT * 
                           FROM airquality where timestamp BETWEEN '$data1' AND '$data2' " ,$connection);

$result2 =$connection->query("SELECT * 
                           FROM energy where timestamp BETWEEN '$data1' AND '$data2' " ,$connection);
                           
 echo '<table class="airqualitytable">
        <tr><th>TimeStamp</th>
        <th>Temp</th>
        <th>Humidity</th>
        <th>Humidex</th>
        </tr>';
while($row = mysql_fetch_array($result))
{
    if($row['temperature']<20 || $row['temperature']>28){
    echo '
    <tr style="background-color:red"><td>'.$row['timestamp'].'</td><td>'.$row['temperature'].'</td><td>'.$row['humidity'].'</td><td>'.$row['humidex'].'</td></tr>';// выводим данные
    }
    else{
    echo '
    <tr><td>'.$row['timestamp'].'</td><td>'.$row['temperature'].'</td><td>'.$row['humidity'].'</td><td>'.$row['humidex'].'</td></tr>';// выводим данные
    }
}
echo '</table>';
 echo '<table class="energytable">
        <tr><th>TimeStamp</th>
        <th>energy</th>
        <th>carbon</th>
        
        </tr>';
while($row = mysql_fetch_array($result2))
{
    echo '
    <tr><td>'.$row['timestamp'].'</td><td>'.$row['energy'].'</td><td>'.$row['carbon'].'</td></tr>';// выводим данные
}
echo '</table>'
?>
   
</body>
</html>
