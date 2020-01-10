<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Registration;
use App\Entity\Training;
use App\Entity\User;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/profiel")
     */
    public function showProfiel(){
        return $this->render('lid/gegevensbeheer.html.twig');
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
}