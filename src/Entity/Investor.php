<?php

namespace App\Entity;

use App\Repository\InvestorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvestorRepository::class)
 */
class Investor
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
    private $wallet_amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $investor_id) : self
    {
        $this->id = $investor_id;
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

    public function getWalletAmount(): ?string
    {
        return $this->wallet_amount;
    }

    public function setWalletAmount(string $wallet_amount): self
    {
        $this->wallet_amount = $wallet_amount;

        return $this;
    }
}
