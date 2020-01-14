<?php


namespace App\Controller;


use App\Entity\Training;
use App\Form\TrainingToevoegenFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */
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