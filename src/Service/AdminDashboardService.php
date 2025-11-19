<?php
namespace App\Service;

use App\Repository\LeaveRequestRepository;
use App\Repository\ProjectRepository;
use App\Repository\ClientRepository;
use App\Repository\InvoiceRepository;
use App\Repository\InventoryRepository;

class AdminDashboardService
{
    public function __construct(
        private LeaveRequestRepository $leaveRequestRepository,
        private ProjectRepository $projectRepository,
        private ClientRepository $clientRepository,
        private InvoiceRepository $invoiceRepository,
        private InventoryRepository $inventoryRepository
    ) {}

    public function getStats(int $year): array
    {
        return [
            'pendingLeaves' => $this->leaveRequestRepository->countPendingRequests(),
            'totalProjects' => $this->projectRepository->totalProjects(),
            'activeClients' => $this->clientRepository->activeClients(),
            'pendingInvoices' => $this->invoiceRepository->pendingInvoices(),
            'inventoryPerCategory' => $this->inventoryRepository->getInventoryGroupedByCategory(),
            'monthlyProjects' => $this->projectRepository->getMonthlyCompletedProjects($year),
        ];
    }
}

