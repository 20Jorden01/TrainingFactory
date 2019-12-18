<?php


namespace App\Controller;


use App\Entity\Training;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LessenController extends AbstractController
{
    /**
     * @Route("/inschrijven")
     */
    public function show(TrainingRepository $training){
        $repository = $this->getDoctrine()->getRepository(Training::class);
        $trainingen = $repository->findAll();


        return $this->render('lessen.html.twig', [
            'trainingen' => $trainingen,
        ]);
    }


}