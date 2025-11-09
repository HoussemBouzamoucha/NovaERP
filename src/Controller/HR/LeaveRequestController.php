<?php

namespace App\Controller\HR;

use App\Entity\LeaveRequest;
use App\Form\LeaveRequestType;
use App\Repository\LeaveRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/leave/request')]
final class LeaveRequestController extends AbstractController
{
    #[Route(name: 'app_leave_request_index', methods: ['GET'])]
    public function index(LeaveRequestRepository $leaveRequestRepository): Response
    {
        return $this->render('leave_request/index.html.twig', [
            'leave_requests' => $leaveRequestRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_leave_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $leaveRequest = new LeaveRequest();
        $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($leaveRequest);
            $entityManager->flush();

            return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('leave_request/new.html.twig', [
            'leave_request' => $leaveRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_leave_request_show', methods: ['GET'])]
    public function show(LeaveRequest $leaveRequest): Response
    {
        return $this->render('leave_request/show.html.twig', [
            'leave_request' => $leaveRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_leave_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('leave_request/edit.html.twig', [
            'leave_request' => $leaveRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_leave_request_delete', methods: ['POST'])]
    public function delete(Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$leaveRequest->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($leaveRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_leave_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
