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

    public function findAll()
    {
      $conn = $this->getEntityManager()->getConnection();

      $sql = "
        SELECT 
        l.id,
        l.category_id,
        c.name category,
        l.item_id,
        i.name item,
        l.operation,
        l.value,
        l.description,
        l.details,
        l.date
        
        FROM log l
        LEFT JOIN category c
        ON l.category_id = c.id
        
        LEFT JOIN item i
        ON l.item_id = i.id
        
        ORDER BY l.id DESC
        ;
      ";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAllAssociative();
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
    public function currentMonthCategoriesPlannedVsReal(): array
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

    // graph 2
    public function currentMonthCategoriesCostsPercentage(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
          SELECT
          strftime('%Y','now') as current_year,
          strftime('%Y', date) as year,
          strftime('%m','now') as current_month,
          strftime('%m', date) as month,
          c.name as category_name,
          SUM(l.value) as log_total
          
          FROM category c
          
          LEFT JOIN log l
          on c.id = l.category_id
          
          WHERE l.operation = 'debit'
          AND year = current_year
          AND month = current_month
          
          GROUP BY c.name
          ORDER BY month
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sum_total = 0;
        foreach($stmt as $value) {
          $result['labels'][] = $value['category_name'];
          $result['log_total'][] = $value['log_total'];
          $sum_total += $value['log_total'];
          $result['sum_total'] = $sum_total;
        }

        foreach ($result['log_total'] as $value) {
          $result['percentages_total'][] = round(($value * 100) / $result['sum_total']);
        }

        return $result;
    }    


    // graphs 3 - Current month - Items per cat - Planned vs Real
    public function currentMonthItemsPerCategoryPlannedVsReal(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
          SELECT
          strftime('%Y','now') as current_year,
          strftime('%Y', date) as year,
          strftime('%m','now') as current_month,
          strftime('%m', date) as month,
          c.name as category_name,
          i.name as item_name,
          SUM(l.value) as log_total,
          b.value as budget_total
          
          FROM log l
          
          JOIN item i
          ON l.item_id = i.id
          
          LEFT JOIN category c
          on l.category_id = c.id
          
          LEFT JOIN budget b
          on i.id = b.item_id
          
          WHERE l.operation = 'debit'
          AND year = current_year
          AND month = current_month
          
          GROUP BY i.name
          ORDER BY c.name
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sum_total = 0;
        foreach($stmt as $value) {
          $result[$value['category_name']]['items'][] = $value['item_name'];
          $result[$value['category_name']]['budget'][] = $value['budget_total'] === null ? 0 : $value['budget_total'];
          $result[$value['category_name']]['log'][] = $value['log_total'];
        }

        return $result;
    }

    // graphs 4 - Current year - Income vs Costs
    public function currentYearIncomeVsCosts(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        // debit
        $sql = "
          SELECT

          strftime('%Y','now') as current_year,
          strftime('%Y', date) as year,
          strftime('%m', date) as month,
          SUM(value) as sum_debit
          
          FROM log
          WHERE operation = 'debit'
          AND year = current_year
          GROUP BY month
          ORDER BY month
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sum_total = 0;
        foreach($stmt as $value) {
          $result[$value['month']]['sum_debit'] = $value['sum_debit'] === null ? 0 : $value['sum_debit'];
        }

        // credit
        $sql = "
          SELECT

          strftime('%Y','now') as current_year,
          strftime('%Y', date) as year,
          strftime('%m', date) as month,
          SUM(value) as sum_credit
          
          FROM log
          WHERE operation = 'credit'
          AND year = current_year
          GROUP BY month
          ORDER BY month
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        foreach($stmt as $value) {
          $result[$value['month']]['sum_credit'] = $value['sum_credit'] === null ? 0 : $value['sum_credit'];
        }

        foreach ($result as $key => $value) {
          !isset($result[${'key'}]['sum_credit']) && $result[${'key'}]['sum_credit'] = 0;
        }

        return $result;
    }        

    // graphs 5 - Current year - Categories - Income vs Costs
    public function currentYearCategoriesCosts(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
          SELECT
          strftime('%Y','now') as current_year,
          strftime('%Y', date) as year,
          strftime('%m', date) as month,
          c.name cat_name,
          -- SUM(l.value) as sum_debit
          l.value
          
          FROM log l
          LEFT JOIN category c
          ON c.id = l.category_id
          
          WHERE l.operation = 'debit'
          AND year = current_year
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        foreach ($stmt as $value) {
          $result['months'][] = $value['month'];

          if(!isset($result['categories'][$value['cat_name']][$value['month']])) {
            $result['categories'][$value['cat_name']][$value['month']] = $value['value'];
          } else {
            $result['categories'][$value['cat_name']][$value['month']] = $result['categories'][$value['cat_name']][$value['month']] + $value['value'];
          }
        }

        $result['months'] = array_unique($result['months']);

        foreach ($result['categories'] as $cat_name => $value) {
          foreach ($result['months'] as $key => $month) {
            if (!array_key_exists($month, $value)) {
              $result['categories'][$cat_name][$month] = 0;
            }
          }

          foreach ($result['categories'] as $cat_name => $value) {
            ksort($result['categories'][$cat_name]);
          }
        }

        return $result;
    }        
}
