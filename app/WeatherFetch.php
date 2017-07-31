<?php
  namespace App;
  class WeatherFetch{
    public static function getTemperatureByCity($city_name,$APIKEY){
      $path="api.openweathermap.org/data/2.5/weather?q=".$city_name."&APPID=".$APIKEY."&units=metric";
      $handler=curl_init($path);
      curl_setopt($handler,CURLOPT_RETURNTRANSFER,1);
      $response=curl_exec($handler);
      return json_decode($response);
    }
  }
?>
