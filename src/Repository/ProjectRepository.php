<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function totalProjects():int{

        return $this-> createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
        }
 public function getMonthlyCompletedProjects(int $year): array
{
    $monthlyCounts = array_fill(1, 12, 0); // Initialize 12 months with 0

    $projects = $this->createQueryBuilder('p')
        ->select('p')
        ->where('p.status = :status')
        ->andWhere('p.endDate_at BETWEEN :start AND :end')
        ->setParameter('status', Project::STATUS_COMPLETED)
        ->setParameter('start', new \DateTimeImmutable("$year-01-01 00:00:00"))
        ->setParameter('end', new \DateTimeImmutable("$year-12-31 23:59:59"))
        ->getQuery()
        ->getResult();

    foreach ($projects as $project) {
        $month = (int)$project->getEndDateAt()->format('n'); // Month 1–12
        $monthlyCounts[$month]++;
    }

    // Ensure keys are 0–11 if your chart library expects it
    return array_values($monthlyCounts);
}




    //    /**
    //     * @return Project[] Returns an array of Project objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Project
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}