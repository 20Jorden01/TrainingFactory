<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegistreerController extends AbstractController
{
    /**
     * @Route("/registreren")
     */
    public function show(){
        return $this->render('registreren.html.twig');
    }
}