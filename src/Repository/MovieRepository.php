<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }
    /**
     * @return Movie[] Returns an array of Movie objects
     */
    public function findAllOrderedByTitle() 
    {
        // pour faire une requete, je dois donner un nom a la table: un alias, comme dans le from sql: FROM table t_alias
        $resultats = $this->createQueryBuilder('m')
        // a partir d ici , on utilise l alias pour representer ma table
        // je trie sur le title de ma table
            ->orderBy('m.title', 'DESC')
            // l avant derniere instruction est de generer la requete
            ->getQuery()
            // et la derniere instruction est d executer la requete 
            // on recoit donc les resultats a partir de la 
            ->getResult();

        return $resultats;

    }
    /**
     * @return Movie[] Returns an array of Movie objects
     */
    public function findAllOrderedByTitleDQL() 
    {
        // le repository ne sait pas faire de DQl(c'est comme sql mais via Doctrine Query Language), on est obligé de contacter le manager 
        // dans le select, on dit que l on veut toute l'entité en utilisant l'alias
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT tagada
            FROM App\Entity\Movie tagada
            ORDER BY tagada.title DESC');
        // returns an array of product objects
        return $query->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Movie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Movie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
