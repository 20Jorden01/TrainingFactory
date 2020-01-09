<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Training;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LidController extends AbstractController
{
    /**
     * @Route("/inschrijven/{slug}")
     */
    public function show(TrainingRepository $training, $slug = null){
        if($slug == null){
            $slug = date('Y-m-d');
        }
        $repository = $this->getDoctrine()->getRepository(Training::class);
        $trainingen = $repository->findAll();
        $lessen = $this->getDoctrine()
            ->getRepository(Lesson::class)
//            ->findLessons(date('Y-m-d'));
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


        return $this->render('lessen.html.twig', [
            'trainingen' => $trainingen,
            'lessen' => $lessen,
            'datums' => $datums,
            'datums2' => $datums2,
        ]);
    }

    /**
     * @Route("/profiel")
     */
    public function showProfiel(){
        return $this->render('lid/gegevensbeheer.html.twig');
    }
}