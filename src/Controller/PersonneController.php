<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    #[Route('/all/{page?1}/{nbre?12}', name: 'personne.all')]
    public function indexAll(ManagerRegistry $doctrine,$page,$nbre): Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findBy([], ['id' => 'ASC'],limit: $nbre,offset: (($nbre*($page-1)))+1);
        $nbPersonnes=$repository->count([]);
        $nbPages = ceil($nbPersonnes/$nbre);
        return $this->render('personne/index.html.twig',[
            'personnes'=>$personnes,
            'nbPages'=>$nbPages,
            'page'=>$page,
            'nbre'=>$nbre,
        ]);
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
    #[Route('/delete/{id<\d+>}', name: 'personne.delete')]
public function deletePersonne (ManagerRegistry $doctrine, Personne $personne=null): RedirectResponse{
        if($personne){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($personne);
            $entityManager->flush();
            $this->addFlash('success',"la personne a été supprimée avec succes ");
        }else{
            $this->addFlash('error'," personne n'existe pas ");
        }
        return $this->redirectToRoute('personne.all');
    }
    #[Route('/update/{id<\d+>}/{firstname}/{lastname}/{age}', name: 'personne.update')]
    public function updatePersonne (ManagerRegistry $doctrine, Personne $personne=null,$firstname,$lastname,$age): RedirectResponse{
        if($personne){
            $entityManager = $doctrine->getManager();
            $personne->setFirstname($firstname);
            $personne->setLastname($lastname);
            $personne->setAge($age);
            $entityManager->persist($personne);
            $entityManager->flush();
            $this->addFlash('success',"la personne a été modifiée avec succes ");
        }else{
            $this->addFlash('error'," personne n'existe pas ");
        }
        return $this->redirectToRoute('personne.all');
    }
}
