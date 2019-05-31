<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "humidex";
    $conn = new mysqli($host, $user, $password, $db);

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'getData':
                getData();
                break;
            case 'getLiveData':
                getLiveData();
                break;
        }
    }

    function getData() {
        $conn = new mysqli($GLOBALS['host'], $GLOBALS['user'], $GLOBALS['password'], $GLOBALS['db']);
        $sql = "SELECT timestamp, temperature, humidity, humidex FROM airquality ORDER BY timestamp DESC LIMIT 10 ";
        $list = $conn -> query($sql);

        if($list === false) {
            trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
            echo "Error";
        } else {
            $mainJSON = array();
            while($row = mysqli_fetch_array($list)){
                $myObj['timestamp'] = $row['timestamp'];
                $myObj['temperature'] = $row['temperature'];
                $myObj['humidity'] = $row['humidity'];
                $myObj['humidex'] = $row['humidex'];
                array_push($mainJSON, $myObj);
            }
            $myJSON = json_encode($mainJSON);
            echo $myJSON;
        }
        exit;
    }

    function getLiveData() {
        $conn = new mysqli($GLOBALS['host'], $GLOBALS['user'], $GLOBALS['password'], $GLOBALS['db']);
        $sql = "SELECT timestamp, temperature, humidity, humidex FROM airquality ORDER BY timestamp DESC LIMIT 1 ";
        $list = $conn -> query($sql);

        if($list === false) {
            trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
            echo "Error";
        } else {
            $mainJSON = array();
            while($row = mysqli_fetch_array($list)){
                $myObj['timestamp'] = $row['timestamp'];
                $myObj['temperature'] = $row['temperature'];
                $myObj['humidity'] = $row['humidity'];
                $myObj['humidex'] = $row['humidex'];
                array_push($mainJSON, $myObj);
            }
            $myJSON = json_encode($mainJSON);
            echo $myJSON;
        }
        exit;
    }
?>
