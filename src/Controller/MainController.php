<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    /**
     * page par default
     * @Route("/", name="main-home")
     * @return Response
     */
    public function home(): Response
    {
        return new Response("putain ca marche !!!!!!");
    }
}