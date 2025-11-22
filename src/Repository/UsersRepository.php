<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Count users by specific role (PostgreSQL compatible)
     *
     * @param string $role The role to count (e.g., 'ROLE_GUEST')
     * @return int
     */
    public function countByRole(string $role): int
    {
        $conn = $this->getEntityManager()->getConnection();
        
        // Use native SQL for PostgreSQL JSON operations
        $sql = "SELECT COUNT(*) FROM users WHERE roles::text LIKE :role";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['role' => '%"' . $role . '"%']);
        
        return (int) $result->fetchOne();
    }

    /**
     * Find users by role (PostgreSQL compatible)
     *
     * @param string $role The role to search for
     * @return Users[]
     */
    public function findByRole(string $role): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        // Get user IDs with the specified role
        $sql = "SELECT id FROM users WHERE roles::text LIKE :role";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['role' => '%"' . $role . '"%']);
        
        $ids = [];
        while ($row = $result->fetchAssociative()) {
            $ids[] = $row['id'];
        }
        
        if (empty($ids)) {
            return [];
        }
        
        // Use QueryBuilder to fetch full entities
        return $this->createQueryBuilder('u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find recent hires (excluding guests)
     *
     * @param int $limit Number of results to return
     * @return Users[]
     */
    public function findRecentHires(int $limit = 10): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        // Get user IDs that don't have ROLE_GUEST
        $sql = "SELECT id FROM users WHERE roles::text NOT LIKE :guest_role ORDER BY created_at DESC LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'guest_role' => '%"ROLE_GUEST"%',
            'limit' => $limit
        ]);
        
        $ids = [];
        while ($row = $result->fetchAssociative()) {
            $ids[] = $row['id'];
        }
        
        if (empty($ids)) {
            return [];
        }
        
        return $this->createQueryBuilder('u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find users with upcoming birthdays (if birthdate field exists)
     *
     * @param int $limit Number of results to return
     * @return Users[]
     */
    public function findUpcomingBirthdays(int $limit = 10): array
    {
        try {
            // Try to query by birthdate if the field exists
            return $this->createQueryBuilder('u')
                ->where('u.birthdate IS NOT NULL')
                ->orderBy('u.birthdate', 'ASC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            // If birthdate field doesn't exist, return some users for demo
            $conn = $this->getEntityManager()->getConnection();
            
            $sql = "SELECT id FROM users WHERE roles::text NOT LIKE :guest_role LIMIT :limit";
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery([
                'guest_role' => '%"ROLE_GUEST"%',
                'limit' => $limit
            ]);
            
            $ids = [];
            while ($row = $result->fetchAssociative()) {
                $ids[] = $row['id'];
            }
            
            if (empty($ids)) {
                return [];
            }
            
            return $this->createQueryBuilder('u')
                ->where('u.id IN (:ids)')
                ->setParameter('ids', $ids)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * Get active employees count (excluding guests)
     *
     * @return int
     */
    public function countActiveEmployees(): int
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = "SELECT COUNT(*) FROM users WHERE roles::text NOT LIKE :guest_role";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['guest_role' => '%"ROLE_GUEST"%']);
        
        return (int) $result->fetchOne();
    }

    /**
     * Find users by department
     *
     * @param int $departmentId
     * @return Users[]
     */
    public function findByDepartment(int $departmentId): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.department', 'd')
            ->where('d.id = :departmentId')
            ->setParameter('departmentId', $departmentId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get users with their department and payroll information
     *
     * @return Users[]
     */
    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.department', 'd')
            ->addSelect('d')
            ->leftJoin('u.payRoll', 'p')
            ->addSelect('p')
            ->leftJoin('u.attendance', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search users by name or email
     *
     * @param string $searchTerm
     * @return Users[]
     */
    public function searchUsers(string $searchTerm): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.firstName LIKE :search')
            ->orWhere('u.lastName LIKE :search')
            ->orWhere('u.email LIKE :search')
            ->setParameter('search', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get employee statistics by department
     *
     * @return array
     */
    public function getEmployeeStatsByDepartment(): array
    {
        return $this->createQueryBuilder('u')
            ->select('d.name as department_name', 'COUNT(u.id) as employee_count')
            ->join('u.department', 'd')
            ->groupBy('d.id', 'd.name')
            ->orderBy('employee_count', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get users by multiple roles (PostgreSQL compatible)
     *
     * @param array $roles Array of roles to search for
     * @return Users[]
     */
    public function findByRoles(array $roles): array
    {
        if (empty($roles)) {
            return [];
        }
        
        $conn = $this->getEntityManager()->getConnection();
        
        // Build conditions for each role
        $conditions = [];
        $params = [];
        foreach ($roles as $index => $role) {
            $conditions[] = "roles::text LIKE :role{$index}";
            $params["role{$index}"] = '%"' . $role . '"%';
        }
        
        $sql = "SELECT id FROM users WHERE " . implode(' OR ', $conditions);
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery($params);
        
        $ids = [];
        while ($row = $result->fetchAssociative()) {
            $ids[] = $row['id'];
        }
        
        if (empty($ids)) {
            return [];
        }
        
        return $this->createQueryBuilder('u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    /**
     * Alternative method: Get all users and filter by role in PHP
     * Use this if you prefer to avoid native SQL
     *
     * @param string $role
     * @return Users[]
     */
    public function findByRolePhp(string $role): array
    {
        $allUsers = $this->findAll();
        
        return array_filter($allUsers, function(Users $user) use ($role) {
            return in_array($role, $user->getRoles());
        });
    }

    /**
     * Count all users excluding guests - PHP version
     *
     * @return int
     */
    public function countActiveEmployeesPhp(): int
    {
        $allUsers = $this->findAll();
        
        $count = 0;
        foreach ($allUsers as $user) {
            if (!in_array('ROLE_GUEST', $user->getRoles())) {
                $count++;
            }
        }
        
        return $count;
    }
}