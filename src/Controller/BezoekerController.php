<?php


namespace App\Controller;


use App\Entity\Training;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BezoekerController extends AbstractController
{

    /**
     * @Route("/", name="homepagina")
     */
    public function homePagina(){
        return $this->render('bezoeker/homePagina.html.twig');
    }

    /**
     * @Route("/contact")
     */
    public function show(){
        return $this->render('bezoeker/contact.html.twig');
    }

    /**
     * @Route("/gedragsregels")
     */
    public function showGedragRegels(){
        return $this->render('bezoeker/gedragsregels.html.twig');
    }

    /**
     * @Route("/training_aanbod", name="trainingAanbod")
     */
    public function showAanbodTrainingen(){
        $repository = $this->getDoctrine()->getRepository(Training::class);
        $trainingen = $repository->findAll();

        return $this->render('bezoeker/trainingAanbod.html.twig', [
            'trainingen' => $trainingen,
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('bezoeker/registreren.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('bezoeker/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

}