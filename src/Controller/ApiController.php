<?php

namespace App\Controller;

use App\Models\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/movies", name="api_list")
     */
    public function list(): Response
    {
        $movieModel =new Movies();
        $movies = $movieModel->getAllMovies();
        //on renvoit une réponse de type json response, c'est la meme chose que esponse , en plus specfifique car ca rajoute le content type dans les headers
        return $this->json($movies);
    }
}
