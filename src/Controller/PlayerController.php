<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

function getColorByRole(string $prefix)
{
    $firstTwoChars = substr($prefix, 0, 2);
    if ($firstTwoChars === '&4') {
        return 'red';
    } else if ($firstTwoChars === '&1') {
        return 'blue';
    }

    return 'unset';
}

class PlayerController extends AbstractController
{
    #[Route('/playerOne', name: 'app_player_one')]
    public function getPlayerOne(): Response
    {

        /*******
        API CONNECTION
        *******/

        /* Api-GetTyroServ*/
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://vps214.tyrolium.fr/api-tyroserv/?pseudo=TheMaximeSan');
        $content = $response->getContent();
        $dataAPI = json_decode($content, true);
        if ($dataAPI['status'] !== "ok"){
            return $this->json(['status' => 'false','why' => 'Err vps214','data' => null,] );
        }



        $dataResultat = $dataAPI['result'];
        $dataResponse = [
            "player" => [
                "pseudo" => $dataResultat['player']['name'],
                "uuid-tyroserv" => $dataResultat['player']['uuid'],
                "uuid-minecraft" => 'test',
            ],
            "faction" => [
                "id" => $dataResultat['faction']['id'],
                "name" => $dataResultat['faction']['name']
            ],
            "role" => [
                'name' => $dataResultat['roles'][0]['displayName'],
                'color' => getColorByRole($dataResultat['roles'][0]['prefix']),
            ],
            "money" => $dataResultat['money']['wallet'],
            'skin' => [
                'base64' => 'test',
                'slim' => false
            ],
            'capes' => [
                'tyroserv' => [],
                'minecraft' => [],
                'optifine' => []
            ],
            'stats' => []

        ];

        return $this->json(
            [
                'status' => 'true',
                'why' => 'Succes Request',
                'data' => $dataResponse,
            ]
        );
    }



    /*
    {
    "status": "ok",
    "message": "Information de TheMaximeSan",
    "result": {
        "player": {
            "uuid": "03beaaca-7e96-3994-b737-9e0ca3dc15e2",
            "name": "TheMaximeSan"
        },
        "faction": {
            "id": "14",
            "name": "TyroFac"
        },
        "money": {
            "wallet": "9500.0"
        },
        "roles": [
            {
                "name": "fonda",
                "displayName": "fondateur",
                "prefix": "&4[Fondateur]&r "
            }
        ],
        }
    }
}


     */






}
