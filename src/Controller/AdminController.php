<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Registration;
use App\Entity\Training;
use App\Entity\User;
use App\Form\InstructeurToevoegenFormType;
use App\Form\RegistrationFormType;
use App\Form\TrainingBewerkenFormType;
use App\Form\TrainingToevoegenFormType;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin/TrainingToevoegen", name="trainingtoevoegen")
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
     * @Route("/admin/trainingen", name="trainingBeheer")
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
     * @Route("/admin/{id}/training-remove", requirements={"id" = "\d+"}, name="deleteTraining")
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
     * @Route("/admin/Training/edit/{id}", name="trainingBewerken")
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

    /**
     * @Route("/admin/leden", name="ledenBeheer")
     */
    public function showLeden(){
        $repository = $this->getDoctrine()->getRepository(User::class);
        $leden = $repository->findAll();

        return $this->render('admin/ledenBeheer.html.twig',[
            'leden' => $leden,
        ]);
    }

    /**
     * @param User $entity
     *
     * @Route("/admin/{id}/lid-remove", requirements={"id" = "\d+"}, name="deleteLid")
     * @return RedirectResponse
     *
     */
    public function deleteLid(User $entity){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->redirectToRoute('ledenBeheer');
    }

    /**
     * @param User $entity
     *
     * @Route("/admin/{id}/lid-resetwachtwoord", requirements={"id" = "\d+"}, name="resetPassword")
     * @return RedirectResponse
     *
     */
    public function resetwachtwoord(User $entity, UserPasswordEncoderInterface $passwordEncoder){
        $entityManager = $this->getDoctrine()->getManager();
        $entity->setPassword($passwordEncoder->encodePassword($entity, "wachtwoord"));
        $entityManager->persist($entity);
        $entityManager->flush();
        return $this->redirectToRoute('ledenBeheer');
    }

    /**
     * @Route("/admin/InstructeurToevoegen", name="toevoegenInstructeur")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $user->setRoles(["ROLE_INSTRUCTEUR"]);
        $form = $this->createForm(InstructeurToevoegenFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword($passwordEncoder->encodePassword($user, 'wachtwoord'));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homepagina');
        }

        return $this->render('admin/intructeurToevoegen.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


}