<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Form\LesToevoegenFormType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($les);
            $entityManager->flush();
        }

        return $this->render('instructeur/lesToevoegen.html.twig', [
            'lesForm' => $form->createView(),
        ]);
    }

}