<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class MainController
{
    /**
     * page par default
     *
     * @return Response
     */
    public function home(): Response
    {
        return new Response("putain ca marche !!!!!!");
    }
}