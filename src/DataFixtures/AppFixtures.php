<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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

            $manager->persist($newMovie);
        }
        // Exécute les requetes SQL
        $manager->flush();
    }
}
