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

    public static function getTemperatureByCityOnDay($city_name,$APIKEY,$day){
      if($day=="TODAY")
        return WeatherFetch::getTemperatureByCity($city_name,$APIKEY);
      $path="api.openweathermap.org/data/2.5/forecast?q=".$city_name."&APPID=".$APIKEY."&units=metric&cnt=5";
      $handler=curl_init($path);
      curl_setopt($handler,CURLOPT_RETURNTRANSFER,1);
      $response=curl_exec($handler);
      $information=json_decode($response);
      return $information->list[1];
    }
  }
?>
