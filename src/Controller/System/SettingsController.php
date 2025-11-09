<?php

namespace App\Controller\System;

use App\Entity\Settings;
use App\Form\SettingsType;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/settings')]
final class SettingsController extends AbstractController
{
    #[Route(name: 'app_settings_index', methods: ['GET'])]
    public function index(SettingsRepository $settingsRepository): Response
    {
        return $this->render('settings/index.html.twig', [
            'settings' => $settingsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_settings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $setting = new Settings();
        $form = $this->createForm(SettingsType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($setting);
            $entityManager->flush();

            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/new.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_settings_show', methods: ['GET'])]
    public function show(Settings $setting): Response
    {
        return $this->render('settings/show.html.twig', [
            'setting' => $setting,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_settings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Settings $setting, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SettingsType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/edit.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_settings_delete', methods: ['POST'])]
    public function delete(Request $request, Settings $setting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$setting->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($setting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
    }
}
