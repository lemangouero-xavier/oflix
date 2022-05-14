<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre/{id}", name="genre", requirements={"id": "\d+"})
     */
    public function index(Genre $genre): Response
    {
        //comme je demande un genre et qu il y a {id} dans la route, le framework va en auto faire un find($id)

        return $this->render('genre/index.html.twig', [
            'genre' => $genre,
        ]);
    }
}
