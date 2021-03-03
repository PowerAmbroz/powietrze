<?php

namespace App\Controller;

use App\Form\CitySelectType;
use App\Services\getAirPolutionData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $fullData;
    private $stationDetails;
    private $main_arr;
    /**
     * @Route("/", name="main")
     * @param getAirPolutionData $apiData
     * @param Request $request
     * @return Response
     */


    public function index(getAirPolutionData $apiData, Request $request): Response
    {

        $stationData = $apiData->getAirPolutionData();

        foreach($stationData as $city){

                $main_arr[$city['city']['name']] = $city['city']['id'];
                $dupli_arr[$city['city']['id']][] = $city;
        }

        $form = $this->createForm(CitySelectType::class, $main_arr);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $id = $form->getData()['city'];
            $stationId = $dupli_arr[$id];

            $stationsData = $this->getStationData($apiData, $stationId);

            $data_arr = $this->stationMrasureData($stationsData);

            $data = array_replace_recursive($this->stationDetails, $data_arr);

            $this->getCityAirPolutionData($data_arr, $data, $apiData);

        }

        return $this->render('main/index.html.twig', [
            'city' => $form->createView(),
            'stationDetails' => $this->fullData
        ]);
    }

    public function getStationData($apiData, $stationId){
        foreach($stationId as $id){

            $this->stationDetails[$id['id']] = array(
                'stationId' => $id['id'],
                'stationName' => $id['stationName'],
                'gegrLat' => $id['gegrLat'],
                'gegrLon' => $id['gegrLon'],
                'addressStreet' => $id['addressStreet'],
                'whatMeasure' => '',
                'pollution' => ''
            ) ;

            $stationsData [] = $apiData->getAirPolutionData($id['id']);
        }
        return $stationsData;
    }

    public function stationMrasureData($stationsData){
        foreach($stationsData as $station){

            foreach($station as $data){
                $data_arr[$data['stationId']]['whatMeasure'][] = $data['param'];
            }
        }
        return $data_arr;
    }

    public function getCityAirPolutionData($data_arr, $data, $apiData){
        foreach($data_arr as $key => $value){
            $air_data [$key]['pollution'] = $apiData->getAirPolutionData(null, $key);
        }

        $this->fullData = array_replace_recursive($data, $air_data);
    }
}
