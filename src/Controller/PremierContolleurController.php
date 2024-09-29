<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PremierContolleurController extends AbstractController
{
    #[Route('/premier', name: 'premier')]
    public function index(): Response
    {
        return $this->render('premier_contolleur/index.html.twig', [
        
        ]);
    }
    #[Route('/hello', name:'hello')]
    public function premierFunction():response
    {
        $rand=rand(0,10);
        echo $rand;
        if($rand % 2 ==0){
           return $this->forward('App\Controller\PremierContolleurController::index');
        }
      return $this->render('premier_contolleur/hello.html.twig');
    }
}
