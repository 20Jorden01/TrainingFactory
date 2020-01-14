<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Registration;
use App\Entity\Training;
use App\Entity\User;
use App\Form\LidBewerkenFormType;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LidController extends AbstractController
{
    /**
     * @Route("/inschrijven/{slug}", name="inschrijven")
     */
    public function show(TrainingRepository $training, $slug = null){
        if($slug == null){
            $slug = date('Y-m-d');
        }
        $repository = $this->getDoctrine()->getRepository(Training::class);
        $trainingen = $repository->findAll();
        $lessen = $this->getDoctrine()
            ->getRepository(Lesson::class)
        ->findLessons(date('Y-m-d', strtotime($slug)));

        $datums = [];
        $datums2 = [];
        for($i = 0;$i <= 7; $i ++){
            array_push($datums, date('Y-m-d', strtotime(' +' . $i .' day')));
            if($i == 0){
                array_push($datums2, 'Vandaag');
                continue;
            }
            array_push($datums2, date('D j M', strtotime(' +' . $i .' day')));
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($this->getUser());
        $registrations = $user->getRegistrations();
        $lidLessen = [];
        foreach ($registrations as $registration){
            array_push($lidLessen, $registration->getLesson()->getId());
        }

        return $this->render('lessen.html.twig', [
            'trainingen' => $trainingen,
            'lessen' => $lessen,
            'datums' => $datums,
            'datums2' => $datums2,
            'lidLessen' => $lidLessen,
        ]);
    }

    /**
     * @Route("/profiel", name="profiel")
     */
    public function showProfiel(){

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($this->getUser());
        $registrations = $user->getRegistrations();

        return $this->render('lid/gegevensbeheer.html.twig', [
            'registrations' => $registrations,
        ]);
    }

    /**
     * @param Lesson $entity
     *
     * @Route("/{id}/entity-add", requirements={"id" = "\d+"}, name="add_route_name")
     * @return RedirectResponse
     *
     */
    public function inschrijven(Lesson $entity){
        $registratie = new Registration();
        $registratie->setPayment($entity->getTrainingId()->getCosts());
        $registratie->setLid($this->getUser());
        $registratie->setLesson($entity);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($registratie);
        $entityManager->flush();
        return $this->redirectToRoute('inschrijven');
    }

    /**
     * @Route("/lid/edit/{id}", name="lidgegevensbewerken")
     */
    public function update($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lid = $entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(LidBewerkenFormType::class, $lid);
        $form->handleRequest($request);

        if (!$lid) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('profiel');
        }

        return $this->render('lid/gegevensbewerken.html.twig', [
            'id' => $lid->getId(),
            'lidbewerkForm' => $form->createView(),
        ]);
    }

    /**
     * @param Registration $entity
     *
     * @Route("/{id}/entity-remove", requirements={"id" = "\d+"}, name="delete_route_name")
     * @return RedirectResponse
     *
     */
    public function deleteActionName(Registration $entity){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->redirectToRoute('profiel');
    }
}