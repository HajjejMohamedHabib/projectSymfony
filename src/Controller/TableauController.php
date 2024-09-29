<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TableauController extends AbstractController
{
    #[Route('/tableau/{nbLignes<\d+>?5}', name: 'app_tableau')]
    public function index($nbLignes): Response
    {
        $notes=[];
        for($i = 0; $i < $nbLignes; $i++) {
            $notes[$i]=rand(0, 20);
        }

        return $this->render('tableau/index.html.twig', [
            'notes'=>$notes,
        ]);
    }
}
