<?php


namespace App\Controller;


use App\Entity\Training;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{

    /**
     * @Route("/contact")
     */
    public function show(){
        return $this->render('contact.html.twig');
    }

    /**
     * @Route("/gedragsregels")
     */
    public function showGedragRegels(){
        return $this->render('gedragsregels.html.twig');
    }

    /**
     * @Route("/training_aanbod")
     */
    public function showAanbodTrainingen(){
        $repository = $this->getDoctrine()->getRepository(Training::class);
        $trainingen = $repository->findAll();

        return $this->render('trainingAanbod.html.twig', [
            'trainingen' => $trainingen,
        ]);
    }

}