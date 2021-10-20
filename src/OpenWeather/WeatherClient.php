<?php
namespace App\OpenWeather;

use App\OpenWeather\Resources\CurrentWeather;

class WeatherClient
{
    /*
     * Initializing all the Weather classes
     *
     * */

    public function __construct()
    {
        $this->currentWeather = new CurrentWeather();
    }

}