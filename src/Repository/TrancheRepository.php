<?php

namespace App\Repository;

use App\Entity\Tranche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Tranche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tranche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tranche[]    findAll()
 * @method Tranche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrancheRepository extends ServiceEntityRepository
{
    private $manager;
    private $entityManager;
    
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Tranche::class);
        $this->entityManager = $registry->getManager();
        $this->manager = $manager;
    }

    public function addTranche(Tranche $tranche): Tranche
    {
        $tranche->setCurrentAmount("0");
        $this->manager->persist($tranche);
        $this->manager->flush();

        return $tranche;
    }

    public function findTrancheIdAndName(): array
    {
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT t.id, t.name, t.interest_rate  FROM Tranche t
            ORDER BY t.name ASC
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        $result = $resultSet->fetchAllAssociative();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $tranche) {
                $data[$tranche['name'] . ' - ' . $tranche['interest_rate'] . '%'] = $tranche['id'];
            }
        }
        return $data;
    }

    public function findAvailableAllowance($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT `maximum_allowance`, `current_amount` FROM Tranche
            WHERE `id` = ' . $id .'
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $data = $resultSet->fetchAllAssociative();
        return $data[0]['maximum_allowance'] - $data[0]['current_amount'];
    }

    public function addCurrentUsage($id, $amount)
    {
        $tranche = $this->entityManager->getRepository(Tranche::class)->find($id);
        $tranche->setCurrentAmount($amount);
        $this->manager->persist($tranche);
        $this->manager->flush();
        return;
    }

    // /**
    //  * @return Tranche[] Returns an array of Tranche objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tranche
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
