<?php

namespace App\Controller\HR;

use App\Entity\Attendance;
use App\Form\AttendanceType;
use App\Repository\AttendanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/attendance')]
final class AttendanceController extends AbstractController
{
    #[Route(name: 'app_attendance_index', methods: ['GET'])]
    public function index(AttendanceRepository $attendanceRepository, EntityManagerInterface $em): Response
    {
        $attendances = $attendanceRepository->findAll();

        // Auto-detect entity fields
        $columns = $em->getClassMetadata(Attendance::class)->getFieldNames();

        return $this->render('crud/list.html.twig', [
            'page_title' => 'Attendances',
            'items' => $attendances,
            'columns' => $columns,
            'entity' => 'attendance',
        ]);
    }

    #[Route('/new', name: 'app_attendance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attendance = new Attendance();
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attendance);
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_index');
        }

        return $this->render('crud/form.html.twig', [
            'page_title' => 'Create Attendance',
            'form' => $form->createView(),
            'submit_label' => 'Save Attendance',
            'entity' => 'attendance',
        ]);
    }

    #[Route('/{id}', name: 'app_attendance_show', methods: ['GET'])]
    public function show(Attendance $attendance, EntityManagerInterface $em): Response
    {
        $columns = $em->getClassMetadata(Attendance::class)->getFieldNames();

        return $this->render('crud/show.html.twig', [
            'page_title' => 'Attendance Details',
            'item' => $attendance,
            'columns' => $columns,
            'entity' => 'attendance',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attendance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_index');
        }

        return $this->render('crud/form.html.twig', [
            'page_title' => 'Edit Attendance',
            'form' => $form->createView(),
            'submit_label' => 'Update Attendance',
            'entity' => 'attendance',
        ]);
    }

    #[Route('/{id}', name: 'app_attendance_delete', methods: ['POST'])]
    public function delete(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attendance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($attendance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_attendance_index');
    }
}
