<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function gethome(): JsonResponse
    {

        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'msg' => 'Bienvenue sur l\'api du site web de TyroServ'

            ]
        );
    }

    #[Route('/stats', name: 'app_stats')]
    public function getStats(): JsonResponse
    {

        $playerUnique = 100000;
        $playerConnected = 1000;
        $playerMax = 1000;

        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => [
                    'playerUnique' => $playerUnique,
                    'playerConnected' => $playerConnected,
                    'playerMax' => $playerMax
                ]
            ]
        );
    }


    #[Route('/stat-playerunique', name: 'app_stat_player_unique')]
    public function getPlayerUnique(): JsonResponse
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
    public function getPlayerConencted(): JsonResponse
    {

        $playerConnected = 1000;
        $playerMax = 1000;

        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => [
                    'playerConnected' => $playerConnected,
                    'playerMax' => $playerMax
                ]
            ]
        );
    }
}
