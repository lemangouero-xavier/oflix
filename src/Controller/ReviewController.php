<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     *  methode pour l ajout de rewiew
     * @Route("/movie/{id}/review", name="movie_review_add")
     */
    public function show(Movie $movie, Request $request, EntityManagerInterface $doctrine)
    {
        // creation du formulaire pour ajouter un review
        $review = new Review();
        $formulaire = $this->createForm(ReviewType::class, $review);

        // on dit au formulaire de prendre en compte la requete http et de relier les données
        // envoyées à la variable que noous lui avons fournis à la creation du formulaire
        // $review
        $formulaire->handleRequest($request);
        //si le formulaire est renvoyé et qu il est valide
        if( $formulaire->isSubmitted() && $formulaire->isValid()){
            // comme on a commenté movie dans notre formaulaire, on doit faire la liaison
            $review->setmovie($movie);

            $doctrine->persist($review);
            $doctrine->flush();

            $this->redirectToRoute('movie', ['id' => $movie->getId()]);
        }
        
        return $this->renderForm('review/index.html.twig',[
            'movie' => $movie,
            'formulaire' => $formulaire
        ]);
    }
}
