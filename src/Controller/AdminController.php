<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Registration;
use App\Entity\Training;
use App\Form\TrainingBewerkenFormType;
use App\Form\TrainingToevoegenFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
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

    /**
     * @Route("/trainingen", name="trainingBeheer")
     */
    public function showTrainingen(){
        $repository = $this->getDoctrine()->getRepository(Training::class);
        $trainingen = $repository->findAll();

        return $this->render('admin/trainingBeheer.html.twig',[
            'trainingen' => $trainingen,
        ]);
    }

    /**
     * @param Training $entity
     *
     * @Route("/{id}/training-remove", requirements={"id" = "\d+"}, name="deleteTraining")
     * @return RedirectResponse
     *
     */
    public function deleteActionName(Training $entity){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->redirectToRoute('trainingBeheer');
    }

    /**
     * @Route("/Training/edit/{id}", name="trainingBewerken")
     */
    public function update($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $training = $entityManager->getRepository(Training::class)->find($id);

        $form = $this->createForm(TrainingBewerkenFormType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('trainingBeheer');
        }

        return $this->render('admin/trainingBewerken.html.twig', [
            'id' => $training->getId(),
            'trainingbewerkForm' => $form->createView(),
        ]);
    }


}