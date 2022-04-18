<?php

namespace App\Controller;

use App\Models\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    /**
     * changement du theme (couleur noir ou jaune de la navbar)
     * 
     * @Route("/theme/toggle", name="theme_switcher")
     * @return void
     */
    public function themeSwitcher(SessionInterface $session) 
    {
        //todo a déplacer dans le UserController
        //j'ai besoin d une classe géréé par le framework et que cette classe soit instancié/crée en auto 
        //pour cela je vais utiliser le principe d'injection de dépendance
        //de ce fait mon code est dependant d'une classe: SessionInterface
        //objectif: pouvoir changer de theme et stocker le nom theme actif et/ou passer à l'autre theme
        // anciennement $_SESSION['theme]
        // fournit 'netflix' si la clé theme n'existe
        $theme = $session->get('theme', 'netflix');
        if ($theme== 'netflix') {
            $session->set('theme', 'allocine');
        } else {
            $session->set('theme', 'netflix');
        }
        // onredirige l'user vers la home
        //todo ux redirect vers la page courante 
        return $this->redirectToRoute("main_home");

    }
}