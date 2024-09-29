<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session=$request->getSession();
        if(!$session->has('todos')){
            $todos=[
                'achat'=>'acheter clé usb',
                'cours'=>'finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos',$todos);
            $this->addFlash('info',"le todo est bien initialisé");
        }
        return $this->render('todo/index.html.twig',[
        ]);
    }
    #[Route('/add/{name}/{content}', name: 'add.todo')]
    public function addTodo(Request $request,$name,$content): Response{
        $session=$request->getSession();
        if($session->has('todos')){
            $todos=$session->get('todos');
            if(isset($todos[$name])){
                $this->addFlash('error',"c'est todo est deja existe");
            }
            else{
                $todos[$name]=$content;
                $session->set('todos',$todos);
                $this->addFlash('success',"le todo est ajouté avec succés");
            }
        }else{
            $this->addFlash('error',"la liste de todo n'est pas encore initialisée");
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/update/{name}/{content}',name:'update.todo')]
public function updateTodo(Request $request,$name,$content):Response
    {
        $session=$request->getSession();
        if($session->has('todos')){
            $todos=$session->get('todos');
            if(isset($todos[$name])){
                $todos[$name]=$content;
                $session->set('todos',$todos);
                $this->addFlash('success',"todo est mis a jour");
            }else{
                $this->addFlash('error',"ce todo n'existe pas");
            }

        }else{
            $this->addFlash('error',"todos n'est pas encore initialisé");
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/delete/{name}',name:'delete.todo')]
public function deleteTodo(Request $request,$name):Response
    {
        $session=$request->getSession();
        if($session->has('todos')){
            $todos=$session->get('todos');
            if(isset($todos[$name])){
                unset($todos[$name]);
                $session->set('todos',$todos);
                $this->addFlash('success',"le todo est supprimé avec succès");
            }else{
                $this->addFlash('error',"le todo n'existe pas ");
            }

        }else{
            $this->addFlash('error',"le todos n'est pas encore initialisé");
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/reset',name:'reset.todo')]
public function resetTodo(Request $request):Response
    {
        $session=$request->getSession();
        $session->remove('todos');
        $this->addFlash('success',"le todo est reseted");
        return $this->redirectToRoute('app_todo');
    }
     }
