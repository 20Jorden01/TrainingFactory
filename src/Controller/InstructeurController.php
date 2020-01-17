<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Registration;
use App\Form\LesToevoegenFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_INSTRUCTEUR")
 */
class InstructeurController extends AbstractController
{

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
            return $this->redirectToRoute('lessenBeheer');
        }

        return $this->render('instructeur/lesToevoegen.html.twig', [
            'lesForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/lessen", name="lessenBeheer")
     */
    public function showLessen(){
        $repository = $this->getDoctrine()->getRepository(Lesson::class);
        $lessen = $repository->findBy(['intructor' => $this->getUser()], ['date' => 'ASC', 'training' => 'ASC', 'time' => 'ASC']);
        $repository2 = $this->getDoctrine()->getRepository(Registration::class);
        $test = $repository2->findAll();


        return $this->render('instructeur/lessen.html.twig',[
            'lessen' => $lessen,
            'registrations' => $test,
        ]);
    }

    /**
     * @param Lesson $entity
     *
     * @Route("/{id}/entity-remove", requirements={"id" = "\d+"}, name="deleteLes")
     * @return RedirectResponse
     *
     */
    public function deleteActionName(Lesson $entity){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->redirectToRoute('lessenBeheer');
    }

    /**
     * @Route("/lesbewerken/{id}", name="bewerkLes")
     */
    public function showlesBewerken(Request $request, $id){
        $entityManager = $this->getDoctrine()->getManager();
        $les = $entityManager->getRepository(Lesson::class)->find($id);
        $form = $this->createForm(LesToevoegenFormType::class, $les);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $les = $form->getData();
            $entityManager->persist($les);
            $entityManager->flush();
            return $this->redirectToRoute('lessenBeheer');
        }

        return $this->render('instructeur/lesBewerken.html.twig', [
            'lesForm' => $form->createView(),
        ]);
    }

}