<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $itemName = null;

    #[ORM\Column(length: 255)]
    private ?string $SKU = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $SupplierName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastUpdated_at = null;

    #[ORM\ManyToOne(inversedBy: 'Inventory')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Supplier $Supplier = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'inventories')]
    private Collection $Project;

    public function __construct()
    {
        $this->Project = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(string $itemName): static
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getSKU(): ?string
    {
        return $this->SKU;
    }

    public function setSKU(string $SKU): static
    {
        $this->SKU = $SKU;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSupplierName(): ?string
    {
        return $this->SupplierName;
    }

    public function setSupplierName(string $SupplierName): static
    {
        $this->SupplierName = $SupplierName;

        return $this;
    }

    public function getLastUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->lastUpdated_at;
    }

    public function setLastUpdatedAt(\DateTimeImmutable $lastUpdated_at): static
    {
        $this->lastUpdated_at = $lastUpdated_at;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->Supplier;
    }

    public function setSupplier(?Supplier $Supplier): static
    {
        $this->Supplier = $Supplier;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProject(): Collection
    {
        return $this->Project;
    }

    public function addProject(Project $project): static
    {
        if (!$this->Project->contains($project)) {
            $this->Project->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        $this->Project->removeElement($project);

        return $this;
    }
}
