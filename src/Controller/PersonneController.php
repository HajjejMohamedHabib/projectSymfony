<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\services\MailerService;
use App\services\UploaderService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/liste', name: 'personne.liste')]
    public function index(ManagerRegistry $doctrine): Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
    }
    #[Route('/liste/age/{ageMin}/{ageMax}', name: 'personne.liste.age')]
    public function DisplayByAgeInterval(ManagerRegistry $doctrine,$ageMin,$ageMax): Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonnesByAgeInterval($ageMin,$ageMax);
        return $this->render('personne/index.html.twig',[
            'personnes'=>$personnes,
        ]);
    }
    #[Route('/stat/age/{ageMin}/{ageMax}', name: 'personne.stat.age')]
    public function statByAgeInterval(ManagerRegistry $doctrine,$ageMin,$ageMax): Response{
        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statPersonnesByAgeInterval($ageMin,$ageMax);
        return $this->render('personne/stats.html.twig',[
            'stats'=>$stats[0],
             'ageMin'=>$ageMin,
              'ageMax'=>$ageMax,
        ]);
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
            'isPagination'=>true
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
    public function addPersonne(ManagerRegistry $doctrine,
                                Request $request,
                                UploaderService $uploaderService,
                                MailerService $mailerService,
                                #[Autowire('%kernel.project_dir%/public/uploads/personnes')]
                                string $brochuresDirectory
    ): Response
    {

        $personne=new Personne();
        $form=$this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var UploadedFile $brochureFile
             */
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                /*$originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                try {
                    $photo->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    echo $e->getMessage();// ... handle exception if something happens during file upload
                }*/
               $newFilename= $uploaderService->uploadFile($photo,$brochuresDirectory);
                $personne->setImage($newFilename);
            }
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();
            $message=$personne->getFirstname().' '.$personne->getLastname().' '.'a été ajouté avec succès';
            $mailerService->sendEmail(subject:'Ajout de user',message: $message);
            $this->addFlash("succes", $personne->getFirstname()." a été ajoutée avec succes");
            return $this->redirectToRoute('personne.all');
        }



        return $this->render('personne/add-personne.html.twig', [
         'form' => $form->createView()
        ]);
    }
    #[Route('/update/{id<\d+>}', name: 'personne.update')]
    public function updatePersonne (ManagerRegistry $doctrine,
                                    Personne $personne=null,
                                    Request $request,
                                    UploaderService $uploaderService,
                                    #[Autowire('%kernel.project_dir%/public/uploads/personnes')]
                                    string $brochuresDirectory): Response{

        $form=$this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var UploadedFile $brochureFile
             */
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $newFilename= $uploaderService->uploadFile($photo,$brochuresDirectory);
                $personne->setImage($newFilename);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($personne);
            $entityManager->flush();
            $this->addFlash('success',$personne->getFirstname()."a été modifiée avec succes ");
            return $this->redirectToRoute('personne.all');
        }

        return $this->render('personne/add-personne.html.twig',[
            'form'=> $form->createView()

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

}
