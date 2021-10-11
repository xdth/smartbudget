<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    // /**
    //  * @return Log[] Returns an array of Log objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Log
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function myTestFunctionz()
    {
        return "yo";
    }

    public function globalTotalPerMonth(string $operation): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT

            strftime('%Y','now') as current_year,
            strftime('%Y', date) as year,
            strftime('%m', date) as month,
            SUM(value)
            
            FROM log
            WHERE operation = :operation
            AND year = current_year
            GROUP BY month
            ORDER BY month;            
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['operation' => $operation]);

        return $stmt->fetchAllAssociative();
    }

    // graph 1 - current month - categories - planned vs real
    public function graph1_currentMonthCategoriesPlannedVsReal(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
          SELECT
          strftime('%Y','now') as current_year,
          strftime('%Y', date) as year,
          strftime('%m','now') as current_month,
          strftime('%m', date) as month,
          c.name as category_name,
          SUM(l.value) as log_total,
          b.value as budget_total
          
          FROM category c
          
          LEFT JOIN log l
          on c.id = l.category_id
          
          LEFT JOIN budget b
          on c.id = b.category_id
          
          WHERE l.operation = 'debit'
          AND year = current_year
          AND month = current_month
          
          GROUP BY c.name
          ORDER BY month
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        foreach($stmt as $key => $value) {
          $result['labels'][] = $value['category_name'];
          $result['log_total'][] = $value['log_total'];
          $result['budget_total'][] = $value['budget_total'];
        }

        return $result;
    }    
}
