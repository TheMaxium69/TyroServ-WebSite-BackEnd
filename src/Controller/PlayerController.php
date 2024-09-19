<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

function getColorByRole(?string $prefix)
{
    if ($prefix === null) {
        return null;
    }

    $firstTwoChars = substr($prefix, 0, 2);
    if ($firstTwoChars === '&4') {
        return 'red';
    } else if ($firstTwoChars === '&1') {
        return 'blue';
    }

    return 'unset';
}

function reformeUUID($uuid) {
    return str_replace('-', '', $uuid);
}


class PlayerController extends AbstractController
{
    #[Route('/playerOne/{pseudo}', name: 'app_player_one', methods:['GET'])]
    public function getPlayerOne(string $pseudo): JsonResponse
    {
        if (empty($pseudo)) {
            return $this->json(['status' => 'false','why' => 'Undefine Pseudo','data' => null,] );
        }

        /*******
        API CONNECTION
        *******/

        /* Api-GetTyroServ */
        $url_APITYROSERV = 'http://vps214.tyrolium.fr/api-tyroserv/?pseudo=' . $pseudo;
        $client = HttpClient::create();
        $response = $client->request('GET', $url_APITYROSERV);
        $content = $response->getContent();
        $data_APITYROSERV = json_decode($content, true);
        if ($data_APITYROSERV['status'] !== "ok"){
            return $this->json(['status' => 'false','why' => 'Err vps214','data' => null,] );
        }
        $resultat_APITYROSERV = $data_APITYROSERV['result'];
        if ($resultat_APITYROSERV['player'] === "no player"){

            $url_USERITIUMPLATER = 'http://useritium.fr/api-externe/?controller=TyroServ&task=player&pseudo=' . $pseudo;
            $client = HttpClient::create();
            $response = $client->request('GET', $url_USERITIUMPLATER);
            $content = $response->getContent();
            $data_USERITIUMPLAYER = json_decode($content, true);
            if ($data_USERITIUMPLAYER['status'] !== "true"){
                return $this->json(['status' => 'false','why' => 'Player Undefine','data' => null,] );
            } else {
                $pseudoFinal = $data_USERITIUMPLAYER['result']['pseudo'];
            }
        } else {
            $pseudoFinal = $resultat_APITYROSERV['player']['name'];
        }

        /* Useritium-Externe */
        $url_USERITIUMSKIN = 'http://useritium.fr/api-externe/?controller=TyroServ&task=getSkinByPseudo&pseudo=' . $pseudo;
        $client = HttpClient::create();
        $response = $client->request('GET', $url_USERITIUMSKIN);
        $content = $response->getContent();
        $data_USERITIUMSKIN = json_decode($content, true);
        if ($data_USERITIUMSKIN['status'] !== "true"){
            return $this->json(['status' => 'false','why' => 'Err useritium skin','data' => null,] );
        }
        $resultat_USERITIUMSKIN = $data_USERITIUMSKIN['result'];

        if ($resultat_USERITIUMSKIN['skin'] === "vide"){
            $typeSkin = "base64";
            $skin = "Minecraft Prenium or No Skin";
            $requestMinecraftAPISkin = true;
        } else {
            $typeSkin = "png";
            $skin = $resultat_USERITIUMSKIN['skin'];
            $requestMinecraftAPISkin = false;
        }
        $url_USERITIUMCAPE = 'http://useritium.fr/api-externe/?controller=TyroServ&task=getCapeByPseudo&pseudo=' . $pseudo;
        $client = HttpClient::create();
        $response = $client->request('GET', $url_USERITIUMCAPE);
        $content = $response->getContent();
        $data_USERITIUMCAPE = json_decode($content, true);
        if ($data_USERITIUMCAPE['status'] !== "true"){
            return $this->json(['status' => 'false','why' => 'Err useritium cape','data' => null,] );
        }
        $idCapeSelected = $data_USERITIUMCAPE['result']['cape'];

        /* Api-TyroModCape */
        $url_TYROMODCAPEWIKI = 'http://vps214.tyrolium.fr/capes/wiki.php';
        $client = HttpClient::create();
        $response = $client->request('GET', $url_TYROMODCAPEWIKI);
        $content = $response->getContent();
        $data_ALL_TYROMODCAPE = json_decode($content, true);
        if ($data_ALL_TYROMODCAPE === []) {
            return $this->json(['status' => 'false', 'why' => 'Err tyromod cape', 'data' => null,]);
        }


        $url_TYROMODCAPE = 'http://vps214.tyrolium.fr/capes/player.php?pseudo='. $pseudo . '&idCapeUseritium=' . $idCapeSelected;
        $client = HttpClient::create();
        $response = $client->request('GET', $url_TYROMODCAPE);
        $content = $response->getContent();
        $data_TYROMODCAPE = json_decode($content, true);
        if ($data_TYROMODCAPE === []) {
            return $this->json(['status' => 'false', 'why' => 'Err tyromod cape', 'data' => null,]);
        } else {
            $resultat_TYROMODCAPE = [];

            foreach ($data_TYROMODCAPE as $capePlayer) {
                $tempIsCape = "";

                foreach ($data_ALL_TYROMODCAPE as $capeOne) {

                    if($capeOne['id'] == $capePlayer['idCapes']){
                        $tempIsCape = $capeOne;
                    }

                }


                $resultat_TYROMODCAPE[] = [
                    "idCapes" => $capePlayer['idCapes'],
                    "name" => $tempIsCape['name'],
                    "dateAdded" => $capePlayer['dateAdded'],
                    "isSelected" => $capePlayer['isSelected'],
                    "isShop" => $tempIsCape['isShop'],
                    "capeTexture" => [
                        "type" => "png", /* base64 or png */
                        "texture" => $tempIsCape['url'],
                        "isAnimated" => $tempIsCape['isAnimated'],
                    ],
                ];
            }
        }

        /* API MINECRAFT OFFICIEL */
        $url_MINECRAFTUUID = 'https://api.mojang.com/users/profiles/minecraft/'. $pseudo ;
        $client = HttpClient::create();
        $response = $client->request('GET', $url_MINECRAFTUUID);
        $code = $response->getStatusCode();
        if ($code === 200){
            $content = $response->getContent();
            $data_MINECRAFTUUID = json_decode($content, true);
            if (!empty($data_MINECRAFTUUID['errorMessage'])) {
                $uuidMinecraft = "No prenium";
            } else {
                $uuidMinecraft = $data_MINECRAFTUUID['id'];
            }
        } else {
            $uuidMinecraft = "No prenium";
        }

        $resultat_MINECRAFTCAPE = [];
        if ($requestMinecraftAPISkin && $uuidMinecraft !== "No prenium") {

            $url_MINECRAFTSKIN = 'https://sessionserver.mojang.com/session/minecraft/profile/'. $uuidMinecraft ;
            $client = HttpClient::create();
            $response = $client->request('GET', $url_MINECRAFTSKIN);
            $code = $response->getStatusCode();
            if ($code === 200){
                $content = $response->getContent();
                $data_MINECRAFTSKIN = json_decode($content, true);
                if (!empty($data_MINECRAFTSKIN['errorMessage'])) {
                    $typeSkin = null;
                    $skin = null;
                } else {
                    $jsonBase64 = $data_MINECRAFTSKIN['properties'][0]['value'];


                    $decodedJsonBase64 = base64_decode($jsonBase64);
                    $skinData = json_decode($decodedJsonBase64, true);

                    if (!empty($skinData['textures']['SKIN'])) {
                        $typeSkin = "url";
                        $skin = $skinData['textures']['SKIN']['url'];
                    } else {
                        $skin = null;
                    }

                    if (!empty($skinData['textures']['CAPE'])) {


                        $resultat_MINECRAFTCAPE[] = [
                            "idCapes" => null,
                            "name" => null,
                            "dateAdded" => null,
                            "isSelected" => 1,
                            "isShop" => null,
                            "capeTexture" => [
                                "type" => "url", /* base64 or png */
                                "texture" => $skinData['textures']['CAPE']['url'],
                                "isAnimated" => null,
                            ],
                        ];
                    }
                    
                    
                }
            } else {
                $typeSkin = null;
                $skin = null;
            }

        } else if ($requestMinecraftAPISkin) {
            /* Renvoyé le skin de steeve par default*/
            $typeSkin = null;
            $skin = null;
        }







        /*REPONSE*/
        $dataResponse = [
            "player" => [
                "pseudo" => $pseudoFinal,
                "uuid-tyroserv" => reformeUUID($resultat_APITYROSERV['player']['uuid']) ?? null,
                "uuid-minecraft" => $uuidMinecraft,
            ],
            "faction" => [
                "id" => $resultat_APITYROSERV['faction']['id'] ?? null,
                "name" => $resultat_APITYROSERV['faction']['name'] ?? null
            ],
            "role" => [
                'name' => $resultat_APITYROSERV['roles'][0]['displayName'] ?? null,
                'color' => getColorByRole($resultat_APITYROSERV['roles'][0]['prefix'] ?? null) ,
            ],
            "money" => $resultat_APITYROSERV['money']['wallet'] ?? null,
            'skin' => [
                'type' => $typeSkin, /* base64 or png */
                'texture' => $skin,
                'slim' => $resultat_USERITIUMSKIN['slim']
            ],
            'capes' => [
                'tyroserv' => $resultat_TYROMODCAPE,
                'minecraft' => $resultat_MINECRAFTCAPE,
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




}
