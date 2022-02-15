<?php

namespace App\Entity;

use App\Repository\TrancheRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrancheRepository::class)
 */
class Tranche
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $interest_rate;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $current_amount;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $maximum_allowance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $tranche_id) : self
    {
        $this->id = $tranche_id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getInterestRate(): ?string
    {
        return $this->interest_rate;
    }

    public function setInterestRate(string $interest_rate): self
    {
        $this->interest_rate = $interest_rate;

        return $this;
    }

    public function getCurrentAmount(): ?string
    {
        return $this->current_amount;
    }

    public function setCurrentAmount(string $current_amount): self
    {
        $this->current_amount = $current_amount;

        return $this;
    }

    public function getMaximumAllowance(): ?string
    {
        return $this->maximum_allowance;
    }

    public function setMaximumAllowance(string $maximum_allowance): self
    {
        $this->maximum_allowance = $maximum_allowance;

        return $this;
    }
}
