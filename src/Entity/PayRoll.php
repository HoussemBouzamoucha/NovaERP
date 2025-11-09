<?php

namespace App\Entity;

use App\Repository\PayRollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;

#[ORM\Entity(repositoryClass: PayRollRepository::class)]
class PayRoll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'payRoll')]
    private Collection $employee;

    #[ORM\Column]
    private ?float $baseSalary = null;

    #[ORM\Column(nullable: true)]
    private ?float $bonus = null;

    #[ORM\Column]
    private ?float $deduction = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $month = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $paymentDate_at = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function __construct()
    {
        $this->employee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getEmployee(): Collection
    {
        return $this->employee;
    }

    public function addEmployee(Users $employee): static
    {
        if (!$this->employee->contains($employee)) {
            $this->employee->add($employee);
            $employee->setPayRoll($this);
        }

        return $this;
    }

    public function removeEmployee(Users $employee): static
    {
        if ($this->employee->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getPayRoll() === $this) {
                $employee->setPayRoll(null);
            }
        }

        return $this;
    }

    public function getBaseSalary(): ?float
    {
        return $this->baseSalary;
    }

    public function setBaseSalary(float $baseSalary): static
    {
        $this->baseSalary = $baseSalary;

        return $this;
    }

    public function getBonus(): ?float
    {
        return $this->bonus;
    }

    public function setBonus(?float $bonus): static
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getDeduction(): ?float
    {
        return $this->deduction;
    }

    public function setDeduction(float $deduction): static
    {
        $this->deduction = $deduction;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(?string $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getPaymentDateAt(): ?\DateTimeImmutable
    {
        return $this->paymentDate_at;
    }

    public function setPaymentDateAt(\DateTimeImmutable $paymentDate_at): static
    {
        $this->paymentDate_at = $paymentDate_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
