<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{

    public const STATUS_PENDING = 'Pending';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_PLANNING = 'Planning';

    


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startDate_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endDate_at = null;

    #[ORM\Column]
    private ?float $budget = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'Project')]
    private Collection $users;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $Client = null;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'Project')]
    private Collection $invoices;

    /**
     * @var Collection<int, Inventory>
     */
    #[ORM\ManyToMany(targetEntity: Inventory::class, mappedBy: 'Project')]
    private Collection $inventories;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project')]
    private Collection $tasks;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->inventories = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDateAt(): ?\DateTimeImmutable
    {
        return $this->startDate_at;
    }

    public function setStartDateAt(\DateTimeImmutable $startDate_at): static
    {
        $this->startDate_at = $startDate_at;

        return $this;
    }

    public function getEndDateAt(): ?\DateTimeImmutable
    {
        return $this->endDate_at;
    }

    public function setEndDateAt(\DateTimeImmutable $endDate_at): static
    {
        $this->endDate_at = $endDate_at;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): static
    {
        $this->budget = $budget;

        return $this;
    }


public function getStatus(): ?string
{
    return $this->status;
}

public function setStatus(string $status): static
{
    $allowed = [
        self::STATUS_PENDING,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_PLANNING,
        
    ];

    if (!in_array($status, $allowed, true)) {
        throw new \InvalidArgumentException("Invalid project status: $status");
    }

    $this->status = $status;
    return $this;
}



    /**
     * @return Collection<int, Users>
     */
    public function addUser(Users $user): static
{
    if (!$this->users->contains($user)) {
        $this->users->add($user);
        $user->addProject($this);
    }

    return $this;
}

public function removeUser(Users $user): static
{
    if ($this->users->removeElement($user)) {
        $user->removeProject($this);
    }

    return $this;
}

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): static
    {
        $this->Client = $Client;

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setProject($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getProject() === $this) {
                $invoice->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inventory>
     */
    public function getInventories(): Collection
    {
        return $this->inventories;
    }

    public function addInventory(Inventory $inventory): static
    {
        if (!$this->inventories->contains($inventory)) {
            $this->inventories->add($inventory);
            $inventory->addProject($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): static
    {
        if ($this->inventories->removeElement($inventory)) {
            $inventory->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }
    

  
}


