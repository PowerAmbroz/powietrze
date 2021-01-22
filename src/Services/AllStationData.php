<?php


namespace App\Services;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class AllStationData
{
    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function getAllStationData(): array
    {

        $response = $this->client->request(
            'GET',
//            'https://api.github.com/repos/symfony/symfony-docs'
            'http://api.gios.gov.pl/pjp-api/rest/station/findAll'
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

//        $messages = [
//            'You did it! You updated the system! Amazing!',
//            'That was one of the coolest updates I\'ve seen all day!',
//            'Great work! Keep going!',
//        ];
//
//        $index = array_rand($messages);
//
//        return $messages[$index];
    }
}