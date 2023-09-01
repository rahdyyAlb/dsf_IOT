<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TransactionsProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransactionsProducts>
 *
 * @method TransactionsProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionsProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionsProducts[]    findAll()
 * @method TransactionsProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionsProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionsProducts::class);
    }

    //    /**
    //     * @return TransactionsProducts[] Returns an array of TransactionsProducts objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TransactionsProducts
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
