<?php


namespace App\Controller;


use App\Entity\Training;
use App\Form\TrainingToevoegenFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/TrainingToevoegen")
     */
    public function showTrainingToevoegen(Request $request){
        $training = new Training();
        $form = $this->createForm(TrainingToevoegenFormType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($training);
            $entityManager->flush();
        }

        return $this->render('admin/trainingToevoegen.html.twig', [
            'trainingForm' => $form->createView(),
        ]);
    }
}