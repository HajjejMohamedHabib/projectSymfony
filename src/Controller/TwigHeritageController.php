<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TwigHeritageController extends AbstractController
{
    #[Route('/twig', name: 'app_twig_heritage')]
    public function index(): Response
    {
        return $this->render('twig_heritage/index.html.twig', [

        ]);
    }
    #[Route('/twig/heritage', name: 'heritage')]
    public function heritage(): Response
    {
        return $this->render('twig_heritage/heritage.html.twig', [

        ]);
    }
}
