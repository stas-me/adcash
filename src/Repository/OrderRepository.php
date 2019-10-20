<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }


    /**
     * @param string|null $search
     * @param int|null $period
     * @return mixed
     */
    public function findWithSearch(?string $search, ?int $period){
         $qb = $this->createQueryBuilder('o')
            ->innerJoin('o.user', 'u')
            ->innerJoin('o.product', 'p')
            ->addSelect('u')
            ->addSelect('p');

         if($search){
             $qb->andWhere('u.name LIKE :search OR p.name LIKE :search')
                ->setParameter('search', '%'.$search.'%');
         }

         if($period){
             $date = date('Y-m-d', strtotime('-'. ($period - 1) .' days'));
             $qb->andWhere('o.date > :date')
                 ->setParameter('date', $date);
         }

         $qb->orderBy('o.date', 'DESC');

         return $qb->getQuery()->getResult();


    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
