<?php 
?> 
<!DOCTYPE html>
<html lang="en">
<linl>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    
    <link rel="stylesheet" href= "AnupDangi_2333319_ISA.CSS">
</head>
<body>


<?php

$host = 'www.google.com';
$port = 80;

$connected = @fsockopen($host, $port);
$isNetworkPresent = false;

if ($connected) {
  include("connection.php");
  $isNetworkPresent = true;
} else {
  $isNetworkPresent = false;
}
?>
    <div class="main">
        <div class="play" style="text-align: center;">
        <form id="form" method="POST" action="#">
		    <input type="text" id="search" placeholder="Search your city" name='city'>
		    <input type="submit" id="submit" placeholder="Search" name="search" onclick="updateWeatherData()">
	</form>      <br>
  <button id="get-data" class="btn" onclick=GetData()>Get offline data</button>
  
  <div id="data" ></div>
        </div>   

        <div class="headings" id="create">       
          
            <h4 class="city" id="city" > City:<?php 
            if($isNetworkPresent){
              echo $cityname ;
            }
            ?></h4>
            <h7 class="temp" id="temp"> Temperature: <?php
            if($isNetworkPresent){
              echo @$weather_temperature1 ;
            }
             ?> °C</h7>
            <h7 class="humid" id="humid">Humidity: <?php
            if($isNetworkPresent){
              echo @$weather_humidity1;
            }
             ?> %</h7>
            <h7 class="wind" id="wind">WindSpeed: <?php
            if($isNetworkPresent){
              echo @$weather_wind1 ;
            }
             ?> km/h</h7> 
            <h7 class="pressure" id="pressure">Pressure: <?php 
            if($isNetworkPresent){
              echo @$pressure1;
            }
             ?> Hpa</h7>
            <h7 class="date" id="date">Date: <?php
            if($isNetworkPresent){
              echo @$weather_time1;
            }
             ?></h7> 
        </div>
        
        <div class="history-box">
    <h2 class="history-title">Historical data</h2>
    <table>
        <tr>
            <th>City</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>WindSpeed</th>
            <th>Pressure</th>
            <th>weather_date</th>
        </tr>

        <?php
if($isNetworkPresent){
  $query = "SELECT * FROM weather ORDER BY weather_time DESC";
  $result = $mysql->query($query);
  $dataArray = array();
  
echo "<script>console.log('Data is displaying from database');</script>";

}


?>

<?php
if($isNetworkPresent){
  while ($row = mysqli_fetch_assoc($result)) {
    $dataArray[] = $row;
  }
}

?>
 <?php 

 if($isNetworkPresent){
  foreach ($dataArray as $data) { ?>

    <tr >
        <td><?php echo $data['cityname']; ?></td>
        
        <td><?php echo $data['Weather_temperature'];?>°C</td>
        
        <td><?php echo $data['Humidity']; ?>%</td>
        
        <td><?php echo $data['Weather_wind']; ?>km/h</td>
        
        <td><?php echo $data['Pressure']; ?>Hpa</td>
        
        <td><?php echo date('Y-m-d', strtotime($data['weather_time'])); ?></td>
    </tr>
    <?php }
 }        
  ?>
    </table>
</div>
    </div>
    
    <script>
var key = 'weatherdata';
var retrievedData = localStorage.getItem(key);
var res = localStorage.getItem('weatherdata');


var parsedData = retrievedData ? JSON.parse(retrievedData) : {};
parsedData.history = parsedData.history || [];


var existingCityIndex = parsedData.history.findIndex(function(item) {
  if(item){    
  return item.cityname === '<?php 
    if($isNetworkPresent){
       echo $cityname;
    }
    ?>';
  }
});


// Create a new weather data object
var newWeatherData = {
  cityname: '<?php   if($isNetworkPresent){ echo $cityname;} ?>',
  temperature: '<?php   if($isNetworkPresent){ echo $weather_temperature1;} ?>',
  windSpeed: '<?php   if($isNetworkPresent){ echo $weather_wind1; }?>',
  humidity: '<?php   if($isNetworkPresent){ echo $weather_humidity1; }?>',
  pressure: '<?php   if($isNetworkPresent){ echo $pressure1;} ?>',
  date: '<?php   if($isNetworkPresent){ echo $weather_time1;} ?>'
};
if (existingCityIndex !== -1) {
  parsedData.history[existingCityIndex] = newWeatherData;
} else {
  parsedData.history.push(newWeatherData);
}

localStorage.setItem(key, JSON.stringify(parsedData));

function GetData(){
  const button = document.getElementById("get-data");
  const dataDiv = document.getElementById("data");

  const inputValue = document.getElementById("search").value;


  const cityId = document.getElementById("city");
  const tempId = document.getElementById("temp");
  const windId = document.getElementById("wind");
  const humidId = document.getElementById("humid");
  const pressureId = document.getElementById("pressure");
  const dateId = document.getElementById("date");


  const database = localStorage.getItem("weatherdata");
  const data = JSON.parse(database);
  let cityObj = data.history.find(x => x.cityname == inputValue)
      if (database) {
        cityId.innerHTML  = `<h4>City: ${cityObj.cityname}</h4> `;
        tempId.innerHTML  = `<h7>Temperature: ${cityObj.temperature}°C</h7>`;
        windId.innerHTML  = `<h7>windspeed: ${cityObj.windSpeed}km/h</h7>`;
        humidId.innerHTML  = `<h7>Humidity: ${cityObj.humidity}%</h7>`;
        pressureId.innerHTML =`<h7>pressure: ${cityObj.pressure}Hpa</h7>`;
        dateId.innerHTML= `<h7>Date: ${cityObj.date}</h7>`;
      }
      console.log('Data is displaying from local storage')
}
</script>
</body>
</html>