<?php

namespace App\Controller;

use App\Models\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="main_")
 */
class MainController extends AbstractController
{
    /**
     * page par default
     * @Route("/", name="home")
     * @return Response
     */
    public function home(): Response
    {
        $modelMovie = new Movies();
        return $this->render('main/index.html.twig', ["movies" => $modelMovie->getAllMovies()]);
    }
    /**
     * page descriptif du film ou serie
     * @Route("/show/{id}", name="show", requirements={"id": "\d+"})
     * @return Response
     */
    public function show(int $id):Response
    {
        $modelMovie = new Movies();
        $movie = $modelMovie->getMovie($id);
        if ($movie === null) {
            //todo error 404
        }
        return $this->render('main/show.html.twig', 
        [
            "movie" =>  $movie
        ]);
    }
    /**
     * liste de rechercherche de film ou série
     *  @Route("/list", name="list")
     * @return Response
     */
    public function list(): Response 
    {
        $modelMovie = new Movies();
        return $this->render('main/list.html.twig', 
        [
            "movies" => $modelMovie->getAllMovies()
        ]);
    }
    /**
     * user favorites list
     * @Route("/favorites", name="favorites")
     * @return void
     */
    public function favorites()
    {
        return $this->render('main/favorites.html.twig');
    }
}