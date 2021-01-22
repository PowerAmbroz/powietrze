<?php

namespace App\Controller;

use App\Services\AllStationData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param AllStationData $allStationData
     * @return Response
     */
    public function index(AllStationData $allStationData): Response
    {

        $main_arr = [];
        $stationData = $allStationData->getAllStationData();

$a = 0;
        foreach($stationData as $city){
            if($a != 6) {
                $main_arr[$city['city']['name']][] = $city;

                $a++;
            }
        }

//        dump($id_arr);
        dump($main_arr);die;
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
