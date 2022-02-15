<?php

namespace App\Tests;

use App\Entity\Loan;
use App\Entity\Investor;
use App\Entity\Tranche;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use \DateTime;

class LoanTest extends KernelTestCase
{
    private $entityManager;
    private $tranche_name;
    private $investor_name;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->tranche_name = 'Tranche Tester' . uniqid();
        $this->investor_name = 'Investor Tester' . uniqid();
    }
    

    /** @test */
    public function investor_record_can_be_created_in_the_database()
    {
        //set up investor data
        $investorRecord = $this->createInvestor();
        // // Make assertions
        $this->assertEquals($this->investor_name, $investorRecord->getName());
        $this->assertEquals('1000', $investorRecord->getWalletAmount());
    }

    /** @test */
    public function tranche_record_can_be_created_in_the_database()
    {
        //set up tranche data
        $trancheRecord = $this->createTranche();
        // // Make assertions
        $this->assertEquals($this->tranche_name, $trancheRecord->getName());
        $this->assertEquals('3', $trancheRecord->getInterestRate());
        $this->assertEquals('0', $trancheRecord->getCurrentAmount());
        $this->assertEquals('1000', $trancheRecord->getMaximumAllowance());
    }

    /** @test */
    public function loan_record_can_be_created_in_the_database()
    {
        //set up loan data
        $loan = new Loan();
        $investor_id = $this->createInvestor(1);
        $tranche_id = $this->createTranche(1);
        $loan->setInvestAmount('1000');
        $loan->setInvestorId($investor_id);
        $loan->setTrancheId($tranche_id);
        $start_date = \DateTime::createFromFormat('Y-m-d', date('Y-m-j'));
        $end_date = \DateTime::createFromFormat('Y-m-d', date('Y-m-t'));
        $loan->setStartDate($start_date);
        $loan->setEndDate($end_date);
        $this->entityManager->persist($loan);
        $this->entityManager->flush();

        // check record
        $loanRepository = $this->entityManager->getRepository(Loan::class);
        $loanRecord = $loanRepository->findOneBy(['start_date' => \DateTime::createFromFormat('Y-m-d', "2018-09-09")]);
        // // Make assertions
        $this->assertEquals(1000, $loanRecord->getInvestAmount());
        $this->assertEquals($investor_id, $loanRecord->getInvestorId());
        $this->assertEquals($tranche_id, $loanRecord->getTrancheId());
        $this->assertEquals($start_date, $loanRecord->getStartDate());
        $this->assertEquals($end_date, $loanRecord->getEndDate());
    }

    private function createInvestor($returnId = 0)
    {
        //set up investor data
        $investor = new Investor();
        $investor->setName($this->investor_name);
        $investor->setWalletAmount('1000');
        $this->entityManager->persist($investor);
        $this->entityManager->flush();

        // check record
        $investorRepository = $this->entityManager->getRepository(Investor::class);
        $investorRecord = $investorRepository->findOneBy(['name' => $this->investor_name]);
        if ($returnId) {
            return $investorRecord->getId();
        } else {
            return $investorRecord;
        }
    }

    private function createTranche($returnId = 0)
    {
        $tranche = new Tranche();
        $tranche->setName($this->tranche_name);
        $tranche->setInterestRate('3');
        $tranche->setCurrentAmount('0');
        $tranche->setMaximumAllowance('1000');
        $this->entityManager->persist($tranche);
        $this->entityManager->flush();

        // check record
        $trancheRepository = $this->entityManager->getRepository(Tranche::class);
        $trancheRecord = $trancheRepository->findOneBy(['name' => $this->tranche_name]);
        if ($returnId) {
            return $trancheRecord->getId();
        } else {
            return $trancheRecord;
        }
    }

}