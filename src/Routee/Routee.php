<?php
namespace App\Routee;

use App\Http\CurlClient;
use App\Config;
use App\File;

class Routee
{
    /**
     * @var string $DEFAULT_REST_HOST, default route
     */
    public $DEFAULT_REST_HOST = 'https://connect.routee.net';

    /**
     * @const string, credentials file
     */
    const FILE_NAME = 'routee.json';

    /**
     * @var string, http client
     *
     */
    public $client;
    public $headers;

    public function __construct()
    {
        $this->client = new CurlClient([
            'baseUrl' => $this->DEFAULT_REST_HOST,
        ]);
        $this->setHeaders();
    }

    /**
     * Set the default headers
     *
     * @return void
     */

    public function setHeaders() : bool
    {
        if (! $this->isAuthenticated())
        {
            $this->headers = [
                "Authorization: Basic ".$this->getEncodedToken(),
                "Content-Type: application/x-www-form-urlencoded"
            ];

            $this->authenticate();
        }
        else
        {
            $this->headers = [
                "Authorization: Bearer ".$this->getAccessToken(),
                "Content-Type: application/json"
            ];
        }

        return true;

    }
    /**
     * Authenticate Routee and store credentials
     *
     * @return void
     */

    public function authenticate() : void
    {
        $this->client->setBaseUrl('https://auth.routee.net');
        $response = $this->client->post(
            '/oauth/token',
            [
                'grant_type' => 'client_credentials'
            ],
            [
                'headers' => $this->headers
            ]);

        File::createFile(self::FILE_NAME, json_encode([
            'access_token' => $response['access_token'],
            'expire_on' => date("d-m-Y h:i:s", time() + $response['expires_in'])
        ]));

        $this->setHeaders();

    }

    /**
     * Get Authorized header for requests
     *
     * @return array
     */
    public function getDefaultHeaders() : array
    {
        return [
            'headers' => $this->headers
        ];
    }

    /**
     * Check if user is authenticated
     *
     * @return bool
     */

    public function isAuthenticated() : bool
    {
        if (! File::isExists(self::FILE_NAME))
            return false;

        $file = json_decode(File::getFile(self::FILE_NAME), true);

        return strtotime($file['expire_on']) >= strtotime(date("d-m-Y h:i:s"));
    }

    /**
     * Get base64 encoded token
     *
     * @return string
     */
    public function getEncodedToken() : string
    {
        return base64_encode(Config::ROUTEE_API .':'. Config::ROUTEE_SECRET);
    }

    /**
     * Get Access token
     *
     * @return string
     */

    public function getAccessToken(): string
    {
        if (! $this->isAuthenticated())
            $this->authenticate();

        return json_decode(File::getFile(self::FILE_NAME), true)['access_token'];
    }

    /**
     * Http request to send message
     * @param array $data
     * @return mixed
     */

    public function sendSms($data)
    {
        return $this->client->post('/sms', $data, $this->getDefaultHeaders());
    }
}