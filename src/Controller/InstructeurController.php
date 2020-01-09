<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\User;
use App\Form\LesToevoegenFormType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InstructeurController extends AbstractController
{

    /**
     * @Route("/contact")
     */
    public function showContact(){
        return $this->render('contact.html.twig');
    }

    /**
     * @Route("/gedragsregels")
     */
    public function showGedragRegels(){
        return $this->render('gedragsregels.html.twig');
    }

    /**
     * @Route("/lestoevoegen")
     */
    public function showlestoevoegen(Request $request){
        $les = new Lesson();
        $form = $this->createForm(LesToevoegenFormType::class, $les);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $les = $form->getData();
            $les->setIntructor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($les);
            $entityManager->flush();
        }

        return $this->render('instructeur/lesToevoegen.html.twig', [
            'lesForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/lessen")
     */
    public function showLessen(){
        $repository = $this->getDoctrine()->getRepository(Lesson::class);
        $lessen = $repository->findBy(['intructor' => $this->getUser()], ['date' => 'ASC', 'training_id' => 'ASC', 'time' => 'ASC']);


        return $this->render('instructeur/lessen.html.twig',[
            'lessen' => $lessen,
        ]);
    }

    /**
     * @param Lesson $entity
     *
     * @Route("/{id}/entity-remove", requirements={"id" = "\d+"}, name="delete_route_name")
     * @return RedirectResponse
     *
     */
    public function deleteActionName(Lesson $entity){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->redirectToRoute('app_instructeur_showlessen');
    }

}