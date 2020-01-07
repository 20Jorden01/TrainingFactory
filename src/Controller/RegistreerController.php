<?php


namespace App\Controller;


use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegistreerController extends AbstractController
{
    /**
     * @Route("/registreren")
     */
    public function show(){
        return $this->render('registreren.html.twig');
    }

    public function new(Request $request){

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if($form-isSubmitted() && $form->isValid()){
            $task = $form->getData();
            return $this->redirectToRoute('task_success');
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($task);
             $entityManager->flush();
        }
    }
}