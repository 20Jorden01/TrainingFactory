<?php


namespace App\Controller;


use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class homePaginaController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function show(){
        return $this->render('homePagina.html.twig');
    }
}