<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="app_back_movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('back/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie);
            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('back/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on rajoute une notif dans le tableau des notifs,
            $this->addFlash(
                // clé du tableau
                'notice',
                // le message
                'Les modifications ont étés sauvegardées!'
            );
            $entityManager->flush();
            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie);
        } else {
            // on rajoute une notification dans le tableau des notifs
            $this->addFlash(
                // clé du tableau
                'error',
                // le message
                'Petit chenapan, ton token n\'est pas valide!'
            );
        }

        return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
