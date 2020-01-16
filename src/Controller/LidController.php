<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Registration;
use App\Entity\Training;
use App\Entity\User;
use App\Form\LidBewerkenFormType;
use App\Repository\TrainingRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
* Require ROLE_ADMIN for *every* controller method in this class.
*
* @IsGranted("ROLE_USER")
*/
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
        $repository2 = $this->getDoctrine()->getRepository(Registration::class);
        $test = $repository2->findAll();
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
            'registrations' => $test,
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
        $registratie->setPayment($entity->getTraining()->getCosts());
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
     * @Route("/{id}/registration-remove", requirements={"id" = "\d+"}, name="deleteRegistration")
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