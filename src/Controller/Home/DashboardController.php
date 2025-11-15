<?php

namespace App\Controller\Home;

use App\Entity\Inventory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LeaveRequestRepository;
use App\Repository\ProjectRepository;
use App\Repository\ClientRepository;
use App\Repository\InventoryRepository;
use App\Repository\InvoiceRepository;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        Request $request, // <--- Add this
        LeaveRequestRepository $leaveRequestRepository,
        ProjectRepository $projectRepository,
        ClientRepository $clientRepository,
        InvoiceRepository $invoiceRepository,
        InventoryRepository $inventoryRepository
    ): Response
    {
        // Dashboard stats
        $pendingLeaves = $leaveRequestRepository->countPendingRequests();
        $totalProjects = $projectRepository->totalProjects();
        $activeClients = $clientRepository->activeClients();
        $pendingInvoices = $invoiceRepository->pendingInvoices();
        $inventoryPerCategory = $inventoryRepository->getInventoryGroupedByCategory();

        // Get the year from query parameter, default to current year
        $selectedYear = $request->query->getInt('year', (int) date('Y'));
        $monthlyProjects = $projectRepository->getMonthlyCompletedProjects($selectedYear);


        
        return $this->render('dashboard/index.html.twig', [
            'pendingLeaves' => $pendingLeaves,
            'totalProjects' => $totalProjects,
            'activeClients' => $activeClients,
            'pendingInvoices' => $pendingInvoices,
            'inventoryPerCategory' => $inventoryPerCategory,
            'monthlyProjects' => $monthlyProjects,
            'selectedYear' => $selectedYear
        ]);
    }
}
