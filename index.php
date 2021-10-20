<?php



require 'src/bootstrap.php';

use App\OpenWeather\WeatherClient;
use App\Routee\Routee;

$location = "Thessaloniki";
$text = "Muhammad Rizwan Sarfraz and temperature in $location is";
$number = '+92 307 870 6991';

$client = new WeatherClient();

$data = $client->currentWeather->getTemperature($location);
$temperature =  $data['main']['temp'];

$message =  "$text less than 20C. The actual temperature is $temperature";

if ($temperature > 20)
    $message = "$text more than 20C. The actual temperature is $temperature";


$routee = new Routee();

 $data = [
    'body' => $message,
    'to' => $number,
    'from' => 'amdTelecom'
];


$res = $routee->sendSms(json_encode($data));
//var_dump($res);
