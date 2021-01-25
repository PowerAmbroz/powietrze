<?php


namespace App\Services;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiData
{
    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function getApiData($stationId = null, $air = null) : array
    {

        if($stationId === null){
            $url = 'http://api.gios.gov.pl/pjp-api/rest/station/findAll';
        }else{
            $url = 'http://api.gios.gov.pl/pjp-api/rest/station/sensors/'.$stationId;
        }

        if($air != null){
            $url = 'http://api.gios.gov.pl/pjp-api/rest/aqindex/getIndex/'.$air;

        }

        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();

        $contentType = $response->getHeaders()['content-type'][0];

        $content = $response->getContent();

        $content = $response->toArray();

        return $content;

    }
}