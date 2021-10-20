<?php
namespace App\Http;

class CurlClient
{
    /** @var array  Http client configuration */
    public $options;

    /** @var callable  Http client */
    public $curl;

    /** @var string request baseUrl */
    public $baseUrl;

    /** @var array Http client query parameters */
    public $parameters;

    /** @var array Http client request headers*/
    public $headers;

    /**
     * Initialized default configurations
     *
     *  @param array $options,  options to apply to the given http Client.
     */

    public function __construct($options=[])
    {
        $this->baseUrl = $options['baseUrl'] ?? '';
        $this->parameters = $options['parameters'] ?? [];
        $this->headers = $options['headers'] ?? [];

       $this->curl = curl_init();
    }

    /**
     * Set base url
     *
     * @return void
     */

    public function setBaseUrl($url) :void
    {
        $this->baseUrl = $url;
    }

    /**
     * Set http headers
     *
     * @return void
     */
    public function setBaseHeaders($headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Http get request
     * @param string $uri request uri.
     * @param array $options,  options to apply to the given http Client.
     * @return array
     */

    public function get($uri, array $options = []) : array
    {
        $this->curl = curl_init();
        return $this->execute($uri,  $options);
    }

    /**
     * Http Post request
     * @param string $uri request uri.
     * @param array $data Post Data.
     * @param array $options,  options to apply to the given http Client.
     */
    public function post($uri, $data, array $options = [])
    {
        $this->curl = curl_init();
        curl_setopt( $this->curl, CURLOPT_POST, true);
        if (is_array($data))
            $data = http_build_query($data);

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        return $this->execute($uri, $options);
    }

    /**
     * Execute Http Post request
     * @param string $uri request uri.
     * @param array $options,  options to apply to the given http Client.
     */

    private function execute($uri , array $options = [])
    {
        $this->setUrl($uri, $options['parameters'] ?? []);
        $this->setHeaders($options['headers'] ?? []);

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($this->curl);
        curl_close($this->curl);

        return json_decode($response, true);
    }

    /**
     * Making the final url for request
     * @param string $uri request uri.
     * @param array $parameters,  query parameters.
     * @return void|boolean
     */

    private function setUrl($uri, $parameters) : bool
    {
        $this->baseUrl = $this->baseUrl . $uri;

        $this->parameters = array_merge($this->parameters, $parameters);
        if($this->parameters)
            $this->baseUrl .= '?'.http_build_query($this->parameters);

        curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl);
        return true;
    }

    /**
     * Making the final Http headers for request
     * @param array $headers,  query parameters.
     * @return void|boolean
     */
    private function setHeaders($headers) : bool
    {
        $this->headers = array_merge($this->headers, $headers);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

        return true;
    }
}