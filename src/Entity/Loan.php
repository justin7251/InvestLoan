<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $invest_amount;

    /**
     * @ORM\Column(type="date")
     */
    private $start_date;

    /**
     * @ORM\Column(type="date")
     */
    private $end_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $investor_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tranche_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvestAmount(): ?string
    {
        return $this->invest_amount;
    }

    public function setInvestAmount(string $invest_amount): self
    {
        $this->invest_amount = $invest_amount;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getInvestorId(): ?int
    {
        return $this->investor_id;
    }

    public function setInvestorId(int $investor_id): self
    {
        $this->investor_id = $investor_id;

        return $this;
    }

    public function getTrancheId(): ?int
    {
        return $this->tranche_id;
    }

    public function setTrancheId(int $tranche_id): self
    {
        $this->tranche_id = $tranche_id;

        return $this;
    }

    // public static function loadValidatorMetadata(ClassMetadata $metadata)
    // {
    //     $metadata->addPropertyConstraint('invest_amount', new LessThanOrEqualValidator());
    // }
}
