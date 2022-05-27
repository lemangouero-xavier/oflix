<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="movie", requirements={"id": "\d+"})
     */
    public function show(MovieRepository $movieRepository, int $id, CastingRepository $castingRepo): Response
    {
        $movie = $movieRepository->find($id);
        dump($movie);
        //je veux les casting d un film en particulier : $id
        // j utilise le findby por faire un find avec un critere
        // en sql : movie_id = $id
        // je suis en objet / entité
        // je dis donc: fait un filtre sur la propriété 'movie' de l objet 'casting'
        // la valeur de cette propriete doit etre egale à un objet Movie
        // je lui donne l objet pour faire le filtre
        // $criteria: ['propriété' => valeur]
        // $orderby: ['proprieté' => 'ASC/DESC']
        $castingFilterByMovie = $castingRepo->findBy(['movie' => $movie], ['creditOrder' => 'ASC']);
        return $this->render('movie/show.html.twig', [
            
            'movie' => $movie,
            'castingFilterByMovie' => $castingFilterByMovie
        ]);
    }

    /**
     * show all movies
     * @Route("/movies", name="movies")
     * @param MovieRepository $repository
     * @return Response
     */
    public function  showAll(MovieRepository $repository): Response 
    {
        $movies = $repository->findAll();
        //$movies = $repository->findAllOrderedByTitle();
        //$movies = $repository->findAllOrderedByTitleDQL();
        dump($movies);
        return $this->render('movie/list.html.twig', [
            'movies' => $movies
        ]);
    }
    // todo Create
    /**
     * methode de creation
     *
     * @param EntityManagerInterface $doctrine
     * le super manger qui a le droit de faire des moifs dans la bdd
     * @Route("/movie/create", name="movie_create")
     */
    public function create(EntityManagerInterface $doctrine): Response
    {
        
        //je veux pouvoir creer un movie
        $newMovie = new Movie();
        $newMovie->setTitle("Mon voisin totoro II : le retour");
        $newMovie->setDuration(90);
        $newMovie->setType("Film");
        $newMovie->setReleaseDate(new DateTime("now"));
        $newMovie->setSummary("lorem");
        $newMovie->setSynopsis("lorem ipsum");
        $newMovie->setPoster("https://m.media-amazon.com/images/M/MV5BYzJjMTYyMjQtZDI0My00ZjE2LTkyNGYtOTllNGQxNDMyZjE0XkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg");
        //pas besoin de remplir le rating car il est nullable

        // on demande au supermanager de préparer l'insertion donc pour préparer l insertion en base on "persiste" les données
        //! la methode persist() correspond a la methode prepare de PDO càd qu elle ne fait pas les requetes SQL

        $doctrine->persist($newMovie);
        
        // Exécute les requetes SQL
        $doctrine->flush();

        dump($newMovie);
        return $this->redirectToRoute("movies");
    }

    // todo Update
    /**
     * movie update
     * @Route("/movie/update/{id}", requirements={"id"="\d+"}) 
     * @param integer $id
     * @param MovieRepository $movieRepository
     * @param EntityManagerInterface $doctrine
     * @return void
     */
    public function update(int $id, MovieRepository $movieRepository, EntityManagerInterface $doctrine)
    {
        //on va rechercher l'enregistrement
        $movie = $movieRepository->find($id);
        // todo gerer la 404

        // on modifie ce que l on veut
        $movie->setTitle('Avatar' . mt_rand(2, 99));
        // pas besoin de faire un persist car le manager connait deja l objet 
        //on execute la requete
        $doctrine->flush();

        return $this->redirectToRoute('movie', array("id" => $id));
    }

    // todo Delete
    /**
     * movie delete
     * @Route("/movie/delete/{id}", requirements={"id"="\d+"}) 
     * @param integer $id
     * @param MovieRepository $movieRepository
     * @param EntityManagerInterface $doctrine
     * @return void
     */
    public function delete(int $id, MovieRepository $movieRepository, EntityManagerInterface $doctrine)
    {
        //on va rechercher l'enregistrement
        $movie = $movieRepository->find($id);
        // todo gerer la 404
        // on demande au manager de supprimer l'id
        $doctrine->remove($movie);
        // on execute la requete
        $doctrine->flush();

        return $this->redirectToRoute("movies");
    }
}
