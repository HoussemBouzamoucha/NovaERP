<?php

namespace App\Entity;

use App\Repository\AttendanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendanceRepository::class)]
class Attendance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'attendance')]
    private Collection $employee;

    #[ORM\Column]
    private ?\DateTimeImmutable $checkIn_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $checkOut_at = null;

    #[ORM\Column]
    private ?float $totalHours = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_at = null;

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
            $employee->setAttendance($this);
        }

        return $this;
    }

    public function removeEmployee(Users $employee): static
    {
        if ($this->employee->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getAttendance() === $this) {
                $employee->setAttendance(null);
            }
        }

        return $this;
    }

    public function getCheckInAt(): ?\DateTimeImmutable
    {
        return $this->checkIn_at;
    }

    public function setCheckInAt(\DateTimeImmutable $checkIn_at): static
    {
        $this->checkIn_at = $checkIn_at;

        return $this;
    }

    public function getCheckOutAt(): ?\DateTimeImmutable
    {
        return $this->checkOut_at;
    }

    public function setCheckOutAt(\DateTimeImmutable $checkOut_at): static
    {
        $this->checkOut_at = $checkOut_at;

        return $this;
    }

    public function getTotalHours(): ?float
    {
        return $this->totalHours;
    }

    public function setTotalHours(float $totalHours): static
    {
        $this->totalHours = $totalHours;

        return $this;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->date_at;
    }

    public function setDateAt(\DateTimeImmutable $date_at): static
    {
        $this->date_at = $date_at;

        return $this;
    }
}
