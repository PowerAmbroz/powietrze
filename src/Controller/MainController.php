<?php

namespace App\Controller;

use App\Services\ApiData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param ApiData $apiData
     * @return Response
     */
    public function index(ApiData $apiData): Response
    {

        $stationData = $apiData->getApiData();

$a = 0;
        foreach($stationData as $city){
            if($a != 6) {
                $main_arr[$city['city']['id']][] = $city;
                $a++;
            }
        }

//        Lista miast i stacji pomiarowych w danym mieście
        dump($main_arr);
        $stationId = $main_arr[1064];
//        dump($stationId);die;
        foreach($stationId as $id){
//            dump($id);die;
            $stationsData [] = $apiData->getApiData($id['id']);
        }

//        $sensorsData = $apiData->getApiData($sensorId);

//        dump($stationsData[0]);die;

        foreach($stationsData as $station){

            foreach($station as $data){
                $data_arr[$data['stationId']][] = $data['param'];
            }
//            die;
//            $station_arr[$station['stationId']][] = $station['id'];
        }

        // Lista Stanowisk pomiarowych w stacjach
        dump($data_arr);

        foreach($data_arr as $key => $value){
            $air_data [] = $apiData->getApiData(null, $key);
        }

        dump($air_data);

        die;
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
