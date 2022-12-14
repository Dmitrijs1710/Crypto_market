<?php

namespace App;

use stdClass;

class ApiClass
{
    private string $url;
    private array $parameters;
    private string $apiKey;


    public function __construct(string $url, array $parameters)
    {
        $this->url = $url;
        $this->parameters = $parameters;

        $this->apiKey = $_ENV['API_KEY'];
    }

    public function getResponse(): ?string
    {
        $headers = [
            'Accepts: application/json',
            "X-CMC_PRO_API_KEY: {$this->apiKey}"
        ];
        $qs = http_build_query($this->parameters); // query string encode the parameters
        $request = "{$this->url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
// Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));
        $response = curl_exec($curl);
        return $response !== false ? $response : null; // Send the request, save the response
    }
}