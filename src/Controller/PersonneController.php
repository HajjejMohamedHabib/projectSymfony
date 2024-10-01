<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonneController extends AbstractController
{
    #[Route('/addpersonne', name: 'add_personne')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne1=new Personne();
        $personne1->setFirstname('mohamed');
        $personne1->setLastname('hajjej');
        $personne1->setAge(28);
        $personne2=new Personne();
        $personne2->setFirstname('ahmed');
        $personne2->setLastname('hajjej');
        $personne2->setAge(30);
        // ajouter l'operation d'insertion de personne a la transaction
        $entityManager->persist($personne1);
        $entityManager->persist($personne2);
        // execution de la transaction
        $entityManager->flush();
        return $this->render('personne/detail.html.twig', [
         'personne1' => $personne1,'personne2' => $personne2
        ]);
    }
}
