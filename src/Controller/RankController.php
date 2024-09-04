<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankController extends AbstractController
{
    #[Route('/rank', name: 'app_rank')]
    public function index(): Response
    {
        return $this->render('rank/index.html.twig', [
            'controller_name' => 'RankController',
        ]);
    }
}
