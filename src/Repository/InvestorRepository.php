<?php

namespace App\Repository;

use App\Entity\Investor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Investor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investor[]    findAll()
 * @method Investor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestorRepository extends ServiceEntityRepository
{
    private $manager;
    private $entityManager;
    
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Investor::class);
        $this->entityManager = $registry->getManager();
        $this->manager = $manager;
    }

    public function addInvestor(Investor $investor): Investor
    {
        $this->manager->persist($investor);
        $this->manager->flush();

        return $investor;
    }

    public function findInvestorIdAndName(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT i.id, i.name FROM Investor i
            ORDER BY i.name ASC
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        $result = $resultSet->fetchAllAssociative();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $investor) {
                $data[$investor['name']] = $investor['id'];
            }
        }
        return $data;
    }

    public function findById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT `wallet_amount` FROM Investor
            WHERE `id` = ' . $id .'
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative()[0]['wallet_amount'];
    }

    public function reduceWalletAmount($id, $reduce_amount)
    {
        $investor = $this->entityManager->getRepository(Investor::class)->find($id);
        $investor->setWalletAmount($reduce_amount);
        $this->manager->persist($investor);
        $this->manager->flush();
        return;
    }

    public function findRelationData($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                `Loan`.`id` AS `id`,
                `Investor`.`name` AS `investor_name`,
                `Tranche`.`name` AS `tranche_name`,
                `Tranche`.`interest_rate` AS `interest_rate`,
                `Loan`.`invest_amount` AS `invest_amount`,
                `Loan`.`start_date` AS `start_date`,
                `Loan`.`end_date` AS `end_date`
            FROM
                Loan
            INNER JOIN Tranche ON
            (
                Tranche.id = Loan.tranche_id
            )
            INNER JOIN Investor ON
            (
                Investor.id = Loan.investor_id
            )
            WHERE
                Investor.id = ' . $id . '
            GROUP BY
                `Loan`.`id`, `Investor`.`id`
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $data = $resultSet->fetchAllAssociative();
        $this->calculateNextMonthInterest($data);
        return $data;
    }

    private function calculateNextMonthInterest(&$data)
    {
        if (count($data) < 1) {
            return;
        }
        foreach ($data as $key => $loan) {
            $data[$key]['next_month_interest']  = $this->calculateInterest($loan['start_date'], $loan['end_date'], $loan['interest_rate'], $loan['invest_amount']);
        }
        return;
    }

    private function calculateInterest($start_date, $end_date, $interest_rate, $invest_amount)
    {
        $currentDayOfMonth = date('t');
        $starttimestamp = strtotime($start_date);
        $endtimestamp = strtotime($end_date);
        $loan_start_date = $loan_end_date = 0;
        //check start date is current month && end date within current month
        if (date("m", $starttimestamp) === date('m') && date("Y", $starttimestamp) === date('Y')) {
            $loan_start_date = date("j", $starttimestamp);
            if ($loan_start_date == 1) {
                $loan_start_date = 0;
            }
        }
        if (date("m", $endtimestamp) === date('m') && date("Y", $endtimestamp) === date('Y')) {
            $loan_end_date = date("d", $endtimestamp);
        }
        $daily_interest_rate = (int)$interest_rate / $currentDayOfMonth;
        $total_days = $loan_end_date - $loan_start_date;
        return round($invest_amount * $daily_interest_rate * $total_days / 100, 2);
    }

    // /**
    //  * @return Investor[] Returns an array of Investor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Investor
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
