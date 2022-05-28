<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // pour se connecter en mode sql pur comme je ne peux pas modif les para de la methode load() à cause de l heritage
    // j utilise mon constructeur pour utiliser l injection de dependance et de mander a ce que le FW me fournisse l objet connection
    private $connect;

    public function __construct(Connection $connexion)
    {
        $this->connect = $connexion;
    }
    // fonction qui permet de remettre l incremente a 0 de toute nos tables
    public function truncate() 
    {
        // on desactive la verif des foreign key sinon les truncate ne fonctionneront pas
        $this->connect->executeQuery('SET foreign_key_checks = 0');
        // la fonction truncate remet l increment a 1
        $this->connect->executeQuery('TRUNCATE TABLE casting');
        $this->connect->executeQuery('TRUNCATE TABLE genre');
        $this->connect->executeQuery('TRUNCATE TABLE movie');
        $this->connect->executeQuery('TRUNCATE TABLE movie_genre');
        $this->connect->executeQuery('TRUNCATE TABLE actor');
        $this->connect->executeQuery('TRUNCATE TABLE season');
    }

    public function load(ObjectManager $manager): void
    {
        $this->truncate();

        /******************* Genre **********************/

        // tableau pour reutiliser les genres plus tard

        $allGenre = [];
        $genre = [
            'Action', 'Animation', 'Aventure', 'Comédie', 'Dessin animé', 'Documentaire', 'Drame', 'peplum',
            'Fantastique', 'Historique', 'Policier', 'Romance', 'Science-fiction', 'Thriller', 'Erotique', 'Western'
        ];
        foreach ($genre as $genreName) {
            // nouveau genre
            $genre = new Genre();
            $genre->setName($genreName);

            // on l ajoute a la liste pour usage ulterieur
            $allGenre[] = $genre;

            // on persiste
            $manager->persist($genre);
        }

        /******************* Actor **********************/
        $allActor = [];
        for ($n=0; $n <200; $n++) {
            $actor = new Actor();
            $actor->setFirstname("Prénom #" . $n);
            $actor->setLastname("Nom #" . $n);

            $allActor[] = $actor;
            $manager->persist($actor);
        }

        /******************* Movie **********************/
        // tableau pour utiliser les movies plus (casting)
        $allMovie = [];
        for($i = 1; $i<20; $i++)
        {//je veux pouvoir creer un movie
            $newMovie = new Movie();
            $newMovie->setTitle("Film #" . $i);
            $newMovie->setDuration(rand(90, 180));
            $type = rand(1, 2) == 1 ? 'Film' : 'Série';
            $newMovie->setType($type);
            $newMovie->setReleaseDate(new DateTime("now"));
            $newMovie->setSummary("lorem ncbcbcbrybcerirehfcerik rfhreiufrekfnberh freihfreiufrekf refie ferhfreifhrekfhreu");
            $newMovie->setSynopsis("lorem ipsum ufrioufroei fureiufireo ffireufierufoer u_vuerjjrereoi frejncdshfferuffer ferfherouifher");
            //tres utile pour avoir des images differentes aleatoires pour le dev
            $newMovie->setPoster('https://picsum.photos/id/'.mt_rand(1,100).'/200/300');

            // je veux des saisons pour uniquement les series
            if($type == 'Série')
            {
                $nbSeason = rand(1,5);
                for ($j = 1; $j <= $nbSeason; $j++)
                {
                    $newSeason = new Season();
                    $newSeason->setNumber($j);
                    $newSeason->setEpisodeNumber(mt_rand(6,24));
                    // ne pas oublier de faire un persist pour que le manager prenne connaissance de ce nouvel 
                    $manager->persist($newSeason);
                    $newMovie->addSeason($newSeason);
                }
            }

            /******** Ajout du genre **********/
            // on ajoute de 1 à 3 genres au hasard pour chaque film
            for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                // les doublons sont gerer par la methode addGenre()
                $randomGenre = $allGenre[mt_rand(0, count($allGenre) - 1)];
                $newMovie->addGenre($randomGenre);
            
                //je garde l 'entity pour plus tard
                $allMovie[] = $newMovie;
                $manager->persist($newMovie);
            }

            /******** Ajout Casting **********/
            for ($x=0; $x < 100; $x++){
                // j ai une liste d acteurs $allActor
            //, avec un liste de film, j ai son id(objet) $allMovie
            // je vais creer un casting 
            $casting = new Casting();
            $casting->setRole("Role #" . $x);
            $casting->setCreditOrder($x);
            // je vais lui donner un movie depuis la liste
            $randomMovie = $allMovie[mt_rand(0, count($allMovie) - 1)];
            $casting->setMovie($randomMovie);
            // je vais lui donner un acteur depuis la liste 
            $randomActor = $allActor[mt_rand(0, count($allActor) - 1)];
            $casting->setActor($randomActor);
            // je persist
            $manager->persist($casting);
            // je vais repete de façon random xfois
            }
            

            $manager->persist($newMovie);
        }
        // Exécute les requetes SQL
        $manager->flush();
    }
}
