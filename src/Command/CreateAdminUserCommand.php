<?php
// src/Command/CreateAdminUserCommand.php
namespace App\Command;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates the first admin user respecting the Users entity structure.'
)]
class CreateAdminUserCommand extends Command
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
        $this->em = $em;
        $this->hasher = $hasher;
    }

    protected function configure(): void
    {
        $this->setHelp('This command creates the first admin user. You can modify the username, password, and details here.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = $this->em->getRepository(Users::class);

        // If any user already exists, avoid creating a new admin to prevent PK/sequence issues
        if ($repo->count([]) > 0) {
            $output->writeln('<comment>Users already exist in the database; aborting admin creation to avoid primary key conflicts.</comment>');
            return Command::SUCCESS;
        }

        $admin = new Users();
        $admin->setEmail('admin@admin.com'); // email required
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setDepartment('Administration');
        $admin->setCreatedAt(new \DateTimeImmutable());

        // Hash the password
        if (! $admin instanceof \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface) {
            // Fallback to native password_hash if Users doesn't implement PasswordAuthenticatedUserInterface
            $password = password_hash('admin123', PASSWORD_BCRYPT); // change to a secure password
        } else {
            $password = $this->hasher->hashPassword($admin, 'admin123'); // change to a secure password
        }
        $admin->setPassword($password);

        // Set roles array
        $admin->setRoles(['ROLE_ADMIN']);

        // Persist to database
        $this->em->persist($admin);
        $this->em->flush();

    $output->writeln('<info>Admin user created successfully!</info>');
    $output->writeln('Email: admin@admin.com');
        $output->writeln('Password: admin123');

        return Command::SUCCESS;
    }
}
