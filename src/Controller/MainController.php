<?php

namespace App\Controller;

use App\Services\ApiData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    protected $fullData;

    /**
     * @Route("/", name="main")
     * @param ApiData $apiData
     * @param Request $request
     * @return Response
     */


    public function index(ApiData $apiData, Request $request): Response
    {

        $stationData = $apiData->getApiData();

//$a = 0;
        foreach($stationData as $city){
//            if($a != 6) {
                $main_arr[$city['city']['name']] = $city['city']['id'];
                $dupli_arr[$city['city']['id']][] = $city;
//                $a++;
//            }
        }

        $form = $this->createFormBuilder()
            ->add('city', ChoiceType::class,[
                'choices' => $main_arr,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $id = $form->getData()['city'];
            $stationId = $dupli_arr[$id];

//            dump($dupli_arr);

            foreach($stationId as $id){

                $stationDetails[$id['id']] = array(
                    'stationId' => $id['id'],
                    'stationName' => $id['stationName'],
                    'gegrLat' => $id['gegrLat'],
                    'gegrLon' => $id['gegrLon'],
                    'addressStreet' => $id['addressStreet'],
                    'whatMeasure' => '',
                    'pollution' => ''
                ) ;

                $stationsData [] = $apiData->getApiData($id['id']);
            }

//        $sensorsData = $apiData->getApiData($sensorId);

//        dump($stationsData);die;

            foreach($stationsData as $station){
//                dump($station);die;


                foreach($station as $data){
//                    $data_arr[$data['stationId']][] = $data['param'];
                    $data_arr[$data['stationId']]['whatMeasure'][] = $data['param'];
                }
//            die;
//            $station_arr[$station['stationId']][] = $station['id'];
            }

            // Lista Stanowisk pomiarowych w stacjach
//            dump($stationDetails);
//            dump($stationDetailsstationId']);
//            dump($data_arr);

            $data = array_replace_recursive($stationDetails, $data_arr);

            foreach($data_arr as $key => $value){
                $air_data [$key]['pollution'] = $apiData->getApiData(null, $key);
            }

            $this->fullData = array_replace_recursive($data, $air_data);
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'city' => $form->createView(),
            'stationDetails' => $this->fullData
        ]);
    }
}
