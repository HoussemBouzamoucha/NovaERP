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
    public function index(LeaveRequestRepository $leaveRequestRepository, EntityManagerInterface $em): Response
    {
        $items = $leaveRequestRepository->findAll();
        $fields = $em->getClassMetadata(LeaveRequest::class)->getFieldNames();

        return $this->render('CRUD/list.html.twig', [
            'page_title' => 'Leave Requests',
            'items' => $items,
            'columns' => $fields,
            'entity' => 'leave_request',
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

            return $this->redirectToRoute('app_leave_request_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Create Leave Request',
            'form' => $form->createView(),
            'submit_label' => 'Save', // <-- add this
            'entity' => 'leave_request',

        ]);
    }

    #[Route('/{id}', name: 'app_leave_request_show', methods: ['GET'])]
    public function show(LeaveRequest $leaveRequest, EntityManagerInterface $em): Response
    {
        $fields = $em->getClassMetadata(LeaveRequest::class)->getFieldNames();

        return $this->render('CRUD/show.html.twig', [
            'page_title' => 'Leave Request Details',
            'item' => $leaveRequest,
            'columns' => $fields,
            'entity' => 'leave_request',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_leave_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_leave_request_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Edit Leave Request',
            'form' => $form->createView(),
            'submit_label' => 'Update Leave Request',
            'entity' => 'leave_request',
        ]);
    }

    #[Route('/{id}', name: 'app_leave_request_delete', methods: ['POST'])]
    public function delete(Request $request, LeaveRequest $leaveRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $leaveRequest->getId(), $request->request->get('_token'))) {
            $entityManager->remove($leaveRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_leave_request_index');
    }
}
