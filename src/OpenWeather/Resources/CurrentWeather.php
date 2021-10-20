<?php


namespace App\OpenWeather\Resources;


class CurrentWeather extends Resource
{
    /**
     * Get Temperate by Location name
     * @param string name
     * @return  array
     * */

    public function getTemperature($name) : array
    {
        return $this->client->get("/weather", [
            'parameters' => [
                'q' => $name
            ]
        ]);
    }

}