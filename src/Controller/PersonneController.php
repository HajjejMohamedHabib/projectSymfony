<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/liste', name: 'personne.liste')]
    public function index(ManagerRegistry $doctrine): Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
    }
    #[Route('/detail/{id<\d+>}', name: 'personne.detail')]
    public function detailPersonne (ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);
        if(!$personne){
            $this->addFlash('error',"Personne n'existe pas");
            return $this->redirectToRoute('personne.liste');
        }
        return $this->render('personne/detail.html.twig',['personne'=>$personne]);
    }
    #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne=new Personne();
        $personne->setFirstname('mohamed');
        $personne->setLastname('hajjej');
        $personne->setAge(28);
        // ajouter l'operation d'insertion de personne a la transaction
        $entityManager->persist($personne);
        // execution de la transaction
        $entityManager->flush();
        return $this->render('personne/detail.html.twig', [
         'personne' => $personne
        ]);
    }
}
