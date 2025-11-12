<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Department $department = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'users')]
    private Collection $Project;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'author')]
    private Collection $Invoice;

    /**
     * @var Collection<int, Inventory>
     */
    #[ORM\OneToMany(targetEntity: Inventory::class, mappedBy: 'users')]
    private Collection $Inventory;

    /**
     * @var Collection<int, LeaveRequest>
     */
    #[ORM\OneToMany(targetEntity: LeaveRequest::class, mappedBy: 'users')]
    private Collection $LeaveRequest;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'assignedTo')]
    private Collection $tasks;

    #[ORM\ManyToOne(inversedBy: 'employee')]
    private ?PayRoll $payRoll = null;

    #[ORM\ManyToOne(inversedBy: 'employee')]
    private ?Attendance $attendance = null;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'sender')]
    private Collection $notifications;

    
    public function __construct()
    {
        $this->Project = new ArrayCollection();
        $this->Invoice = new ArrayCollection();
        $this->Inventory = new ArrayCollection();
        $this->LeaveRequest = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->tasks = new ArrayCollection();
        $this->notifications = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user has at least ROLE_GUEST
        $roles[] = 'ROLE_GUEST';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary sensitive data, clear it here
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;
        return $this;
    }   

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    // --- Collections (Projects, Invoice, Inventory, LeaveRequest) ---
    public function getProject(): Collection { return $this->Project; }
    public function addProject(Project $project): static { if (!$this->Project->contains($project)) $this->Project->add($project); return $this; }
    public function removeProject(Project $project): static { $this->Project->removeElement($project); return $this; }

    public function getInvoice(): Collection { return $this->Invoice; }
    public function addInvoice(Invoice $invoice): static { if (!$this->Invoice->contains($invoice)) { $this->Invoice->add($invoice); $invoice->setAuthor($this); } return $this; }
    public function removeInvoice(Invoice $invoice): static { if ($this->Invoice->removeElement($invoice)) { if ($invoice->getAuthor() === $this) { $invoice->setAuthor(null); } } return $this; }

    public function getInventory(): Collection { return $this->Inventory; }
    public function addInventory(Inventory $inventory): static { if (!$this->Inventory->contains($inventory)) { $this->Inventory->add($inventory); $inventory->setUsers($this); } return $this; }
    public function removeInventory(Inventory $inventory): static { if ($this->Inventory->removeElement($inventory)) { if ($inventory->getUsers() === $this) { $inventory->setUsers(null); } } return $this; }

    public function getLeaveRequest(): Collection { return $this->LeaveRequest; }
    public function addLeaveRequest(LeaveRequest $leaveRequest): static { if (!$this->LeaveRequest->contains($leaveRequest)) { $this->LeaveRequest->add($leaveRequest); $leaveRequest->setUsers($this); } return $this; }
    public function removeLeaveRequest(LeaveRequest $leaveRequest): static { if ($this->LeaveRequest->removeElement($leaveRequest)) { if ($leaveRequest->getUsers() === $this) { $leaveRequest->setUsers(null); } } return $this; }

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
            $task->setAssignedTo($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAssignedTo() === $this) {
                $task->setAssignedTo(null);
            }
        }

        return $this;
    }

    public function getPayRoll(): ?PayRoll
    {
        return $this->payRoll;
    }

    public function setPayRoll(?PayRoll $payRoll): static
    {
        $this->payRoll = $payRoll;

        return $this;
    }

    public function getAttendance(): ?Attendance
    {
        return $this->attendance;
    }

    public function setAttendance(?Attendance $attendance): static
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setSender($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getSender() === $this) {
                $notification->setSender(null);
            }
        }

        return $this;
    }

    
}
