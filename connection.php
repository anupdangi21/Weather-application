<?php
header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Portfolio3";

$mysql = new mysqli($servername , $username , $password , $dbname);

if ($mysql -> connect_errno) {
    echo "Failed to connect to MySQL: ".$mysql -> connect_error;
}
 
$create_table=
    'CREATE TABLE IF NOT EXISTS weather(
        id int AUTO_INCREMENT PRIMARY KEY,
        Weather_condition varchar(100) DEFAULT NULL,
        Weather_temperature float DEFAULT NULL,
        Weather_wind float DEFAULT NULL,
        weather_time date DEFAULT NULL,
        cityname varchar(100) DEFAULT NULL,
        Humidity float DEFAULT NULL,
        Pressure float DEFAULT NULL 
        );';

$mysql -> query($create_table);

$check_data = "SELECT *
FROM weather
WHERE weather_time >= DATE_SUB(NOW(), INTERVAL 3600 SECOND)
ORDER BY weather_time DESC limit 1 ";

$result = $mysql ->query($check_data);

// 
if($result->num_rows == 0){

    if(isset($_POST['search'])){
        $cityname = $_POST['city'];
        
        $seven_days_ago = strtotime('-7 days'); // initialize $seven_days_ago variable

        //Truncating the entire sql table so that no repetition of data exists
        $delete_records = "TRUNCATE TABLE weather";
                if($mysql->query($delete_records) === false){
                    echo "Error: " . $sql . "<br>" . $mysql->error;
                }
        
        for($i = 0 ; $i < 7 ; $i++){
            
            $date = date('Y-m-d', $seven_days_ago); // converting timestamp to date string
          
            $unix_timestamp = strtotime($date); // converting date string to Unix timestamp format
            
            $end_date = date('Y-m-d', strtotime("-$i days")); // calculating end date for API request
            
            $start_date = date('Y-m-d', strtotime("-".($i+1)." days")); // calculating start date for API request
        
            $url ="https://api.weatherbit.io/v2.0/history/daily?postal_code=27601&city=".$cityname."&start_date=".$start_date."&end_date=".$end_date."&key=5a3e7d65e7d4449e8b5020422b8e1ed5";
            

            //GEtting the data from the api 
            $data = file_get_contents($url);
            
            //Checking weather the api contains data or not 
            if(!$data){
                header("Location: index.php");
                // echo "Error: Failed to retrieve data from API<br>";
                continue;
            }
            
                $decode = json_decode($data, true);
                $weather_temperature = $decode['data'][0]['temp'];
                $weather_wind = $decode['data'][0]['wind_spd'];
                $cityname = $decode['city_name'];
                $pressure = $decode['data'][0]['pres'];
                $weather_humidity= $decode['data'][0]['rh'];
                $weather_time = $decode['data'][0]['datetime'];
                
                //Inserting into the database
                $sql = "REPLACE INTO weather (Weather_condition, Weather_temperature, Weather_wind, Weather_time, cityname, Pressure, Humidity)
                VALUES (0, '{$weather_temperature}', '{$weather_wind}', '{$weather_time}', '{$cityname}', '{$pressure}', '{$weather_humidity}')";

        
    
                // $insert = $mysql->query($sql);
                if ($mysql->query($sql) === false) {
                    echo "Error: " . $sql . "<br>" . $mysql->error;
                }
                 $seven_days_ago = strtotime('-1 day', $seven_days_ago); // update $seven_days_ago variable
        }
    }
            else{
                $cityname='Huntingdonshire';          
                
                $url1 = "https://api.weatherbit.io/v2.0/current?lat=35.7796&lon=-78.6382&city=%huntingdonshire%22&key=5a3e7d65e7d4449e8b5020422b8e1ed5";
                $data1 = file_get_contents($url1) ;
                $decode1 = json_decode($data1, true);
                $weather_temperature1 = $decode1['data'][0]['temp'];
                $weather_wind1 = $decode1['data'][0]['wind_spd'];
                $pressure1=$decode1['data'][0]['pres'];
                $weather_humidity1=$decode1['data'][0]['rh'];
                $weather_time1 = $decode1['data'][0]['datetime'];
                    
            }
            if(isset($_POST['search'])){
                $cityname = $_POST['city'];
                $url1 = "https://api.weatherbit.io/v2.0/current?lat=35.7796&lon=-78.6382&city=%.$cityname.%22&key=5a3e7d65e7d4449e8b5020422b8e1ed5";
                $data1 = file_get_contents($url1) ;
                $decode1 = json_decode($data1, true);
                $weather_temperature1 = $decode1['data'][0]['temp'];
                $weather_wind1 = $decode1['data'][0]['wind_spd'];
                $pressure1=$decode1['data'][0]['pres'];
                $weather_humidity1=$decode1['data'][0]['rh'];
                $weather_time1 = $decode1['data'][0]['datetime'];

            }
        }
    else{
       echo "No data found!";
    }
