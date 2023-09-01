<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TransactionIteme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransactionIteme>
 *
 * @method TransactionIteme|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionIteme|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionIteme[]    findAll()
 * @method TransactionIteme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionItemeRepository extends ServiceEntityRepository
{
	public function __construct (ManagerRegistry $registry)
	{
		parent::__construct($registry, TransactionIteme::class);
	}

	//    /**
	//     * @return TransactionIteme[] Returns an array of TransactionIteme objects
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

	//    public function findOneBySomeField($value): ?TransactionIteme
	//    {
	//        return $this->createQueryBuilder('t')
	//            ->andWhere('t.exampleField = :val')
	//            ->setParameter('val', $value)
	//            ->getQuery()
	//            ->getOneOrNullResult()
	//        ;
	//    }
}
