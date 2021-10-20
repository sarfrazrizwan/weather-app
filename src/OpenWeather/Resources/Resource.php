<?php

namespace App\OpenWeather\Resources;

use App\Http\CurlClient;
use App\Config;

class Resource
{
    //Rest default configuration
    public $DEFAULT_REST_HOST = "https://api.openweathermap.org/data";
    public $DEFAULT_REST_HOST_VERSION = "/2.5/";

    public $client;

    /**
     * Initialized default configurations
     *
     *  @param array $options,  options to apply to the given http Client.
     */

    public function __construct(array $options = [])
    {
        $this->client = new CurlClient([
            'baseUrl' => $this->DEFAULT_REST_HOST . $this->DEFAULT_REST_HOST_VERSION,
            'parameters' => [
                'units' => $options['units'] ?? Config::OPEN_WEATHER_UNITS,
                'apiKey' => $options['apiKey'] ?? Config::OPEN_WEATHER_API,
            ]
        ]);
    }

    /**
     * Send formatted response
     *
     * @return array
     */
    protected function formatResponse($response) : array
    {
        return json_decode($response, true);
    }
}