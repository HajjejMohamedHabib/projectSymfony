<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    //    /**
    //     * @return PersonneFixture[] Returns an array of PersonneFixture objects
    //     */
       public function findPersonnesByAgeInterval($ageMin,$ageMax): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.age <= :ageMax and p.age >= :ageMin')
                ->setParameter('ageMin', $ageMin)
                ->setParameter('ageMax', $ageMax)
                ->getQuery()
                ->getResult()
            ;
       }
    public function statPersonnesByAgeInterval($ageMin,$ageMax): array
    {
        return $this->createQueryBuilder('p')
            ->select('avg(p.age) as ageMoyenne ','count(p.id) as nbPersonnes')
            ->andWhere('p.age <= :ageMax and p.age >= :ageMin')
            ->setParameter('ageMin', $ageMin)
            ->setParameter('ageMax', $ageMax)
            ->getQuery()
            ->getScalarResult()
         //   ->getResult()
            ;
    }

    //    public function findOneBySomeField($value): ?PersonneFixture
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
