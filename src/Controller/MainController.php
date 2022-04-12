<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * page par default
     * @Route("/", name="main-home")
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('main/index.html.twig', ["information_test" => "l'information que je veux afficher"]);
    }
}