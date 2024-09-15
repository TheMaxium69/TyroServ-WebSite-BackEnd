<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankController extends AbstractController
{


    #[Route('/rank-s1-playerPreview/', name: 'app_rank_s1_player_preview', methods:['GET'])]
    public function getRankS1PlayerPreview(): JsonResponse
    {

        $rank = [
            [
              "pseudo" => "TheMaximeSan",
              "money" => "9500,0",
            ],
            [
              "pseudo" => "TheMaxium69",
              "money" => "9400,0",
            ],
            [
              "pseudo" => "AnaelTech",
              "money" => "9300,0",
            ],
            [
              "pseudo" => "Michel",
              "money" => "9200,0",
            ],
            [
              "pseudo" => "Mario",
              "money" => "9100,0",
            ]
        ];


        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => $rank,
            ]
        );

    }

    #[Route('/rank-s1-player/', name: 'app_rank_s1_player', methods:['GET'])]
    public function getRankS1Player(): JsonResponse
    {

        $rank = [
            [
              "pseudo" => "TheMaximeSan",
              "money" => "9500,0",
            ],
            [
              "pseudo" => "TheMaxium69",
              "money" => "9400,0",
            ],
            [
              "pseudo" => "AnaelTech",
              "money" => "9300,0",
            ],
            [
              "pseudo" => "Michel",
              "money" => "9200,0",
            ],
            [
              "pseudo" => "Mario",
              "money" => "9100,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
            [
              "pseudo" => "Luigi",
              "money" => "9000,0",
            ],
        ];


        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => $rank,
            ]
        );

    }

    #[Route('/rank-s1-faction/', name: 'app_rank_s1_faction', methods:['GET'])]
    public function getRankS1Faction(): JsonResponse
    {

        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => "",
            ]
        );

    }

    #[Route('/rank-s2-player/', name: 'app_rank_s2_player', methods:['GET'])]
    public function getRankS2Player(): JsonResponse
    {

        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => "",
            ]
        );

    }








}
