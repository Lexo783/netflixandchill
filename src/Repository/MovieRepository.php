<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Services\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param Search $search
     * faire une recherche de video par filtre
     */
    public function findWithSearch(Search $search)
    {
        $query = $this->createQueryBuilder('m')
            ->select('g','m')
            ->join('m.genres', 'g');

        if(!empty($search->genre))
        {
            $query = $query->andWhere('g.id IN (:genres)')
                ->setParameter('genres',$search->genre);
        }
        if(!empty($search->string))
        {
            $query = $query->andWhere('m.title LIKE :string')
                ->setParameter('string',"%$search->string%");
        }
        return $query->getQuery()->getResult();
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
