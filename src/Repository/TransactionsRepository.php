<?php

namespace App\Repository;

use App\Entity\Transactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transactions>
 *
 * @method Transactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transactions[]    findAll()
 * @method Transactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transactions::class);
    }
	public function getTotalCashAmount(): float
	{
		$qb = $this->createQueryBuilder('t');
		$qb->select('SUM(t.cashAmount) as totalCashAmount');

		$query = $qb->getQuery();
		$result = $query->getSingleScalarResult();

		return (float) $result;
	}
	public function getTotalChequeAmount(): float
	{
		$qb = $this->createQueryBuilder('t');
		$qb->select('SUM(t.chequeAmount) as totalChequeAmount');

		$query = $qb->getQuery();
		$result = $query->getSingleScalarResult();

		return (float) $result;
	}

	public function getTotalCardAmount(): float
	{
		$qb = $this->createQueryBuilder('t');
		$qb->select('SUM(t.cardAmount) as totalCardAmount');

		$query = $qb->getQuery();
		$result = $query->getSingleScalarResult();

		return (float) $result;
	}
//    /**
//     * @return Transactions[] Returns an array of Transactions objects
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

//    public function findOneBySomeField($value): ?Transactions
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
