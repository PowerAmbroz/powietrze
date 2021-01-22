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

//        if($sensorId != null) {
//            $url = 'http://api.gios.gov.pl/pjp-api/rest/data/getData/'.$sensorId;
//        }
        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;

    }
}