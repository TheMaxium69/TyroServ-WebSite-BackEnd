<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/stats', name: 'app_stats')]
    public function getStats(): Response
    {


        $playerUnique = 100000;
        $playerConnected = 1000;



        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => [
                    'playerUnique' => $playerUnique,
                    'playerConnected' => $playerConnected
                ]
            ]
        );
    }


    #[Route('/stat-playerunique', name: 'app_stat_player_unique')]
    public function getPlayerUnique(): Response
    {

        $playerUnique = 100000;


        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => [
                    'playerUnique' => $playerUnique
                ]
            ]
        );
    }

    #[Route('/stat-playerconnected', name: 'app_stat_player_connected')]
    public function getPlayerConencted(): Response
    {

        $playerConnected = 1000;

        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => [
                    'playerConnected' => $playerConnected
                ]
            ]
        );
    }
}
