<?php

namespace App\Controller\Dashboards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminDashboardService;
use App\Service\HrDashboardService;

final class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'dashboard_admin_index')]
    public function admin(Request $request, AdminDashboardService $service): Response
    {
        $selectedYear = $request->query->getInt('year', (int) date('Y'));
        $stats = $service->getStats($selectedYear);

        return $this->render('dashboard/admin/index.html.twig', $stats + ['selectedYear' => $selectedYear]);
    }

    #[Route('/admin/hr/dashboard', name: 'dashboard_hr_index')]
public function hr(HrDashboardService $hrService): Response
{
    $data = $hrService->getStats();
    
    $data['department_stats'] = $hrService->getDepartmentStats();
    $data['leave_request_stats'] = $hrService->getLeaveRequestStats();
    $data['attendance_stats'] = $hrService->getAttendanceStats(30);
    $data['payroll_summary'] = $hrService->getPayrollSummary();
    $data['employees_by_role'] = $hrService->getEmployeesByRole();

    return $this->render('hr_dashboard/index.html.twig', $data);
}

    #[Route('/admin/finance/dashboard', name: 'dashboard_finance_index')]
    public function finance(): Response
    {
        return $this->render('dashboard/finance/index.html.twig');
    }

    #[Route('/admin/sales/dashboard', name: 'dashboard_sales_index')]
    public function sales(): Response
    {
        return $this->render('dashboard/sales/index.html.twig');
    }

    #[Route('/admin/procurement/dashboard', name: 'dashboard_procurement_index')]
    public function procurement(): Response
    {
        return $this->render('dashboard/procurement/index.html.twig');
    }

    #[Route('/admin/production/dashboard', name: 'dashboard_production_index')]
    public function production(): Response
    {
        return $this->render('dashboard/production/index.html.twig');
    }

    #[Route('/admin/projects/dashboard', name: 'dashboard_projects_index')]
    public function projects(): Response
    {
        return $this->render('dashboard/projects/index.html.twig');
    }

    #[Route('/admin/staff/dashboard', name: 'dashboard_staff_index')]
    public function staff(): Response
    {
        return $this->render('dashboard/staff/index.html.twig');
    }
}