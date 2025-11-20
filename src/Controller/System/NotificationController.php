<?php

namespace App\Controller\System;

use App\Entity\Notification;
use App\Form\NotificationType;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notification')]
final class NotificationController extends AbstractController
{
    #[Route(name: 'app_notification_index', methods: ['GET'])]
    public function index(NotificationRepository $notificationRepository, EntityManagerInterface $em): Response
    {
        $items = $notificationRepository->findAll();
        $columns = $em->getClassMetadata(Notification::class)->getFieldNames();

        return $this->render('CRUD/list.html.twig', [
            'page_title' => 'Notifications',
            'items' => $items,
            'columns' => $columns,
            'entity' => 'notification',
        ]);
    }

    #[Route('/new', name: 'app_notification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notification);
            $entityManager->flush();

            return $this->redirectToRoute('app_notification_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Create Notification',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_notification_show', methods: ['GET'])]
    public function show(Notification $notification, EntityManagerInterface $em): Response
    {
        $columns = $em->getClassMetadata(Notification::class)->getFieldNames();

        return $this->render('CRUD/show.html.twig', [
            'page_title' => 'Notification Details',
            'item' => $notification,
            'columns' => $columns,
            'entity' => 'notification',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notification $notification, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_notification_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Edit Notification',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_notification_delete', methods: ['POST'])]
    public function delete(Request $request, Notification $notification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notification->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notification_index');
    }
}
