<?php

namespace App\Controller\HR;

use App\Entity\PayRoll;
use App\Form\PayRollType;
use App\Repository\PayRollRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pay/roll')]
final class PayRollController extends AbstractController
{
    #[Route(name: 'app_pay_roll_index', methods: ['GET'])]
    public function index(PayRollRepository $payRollRepository): Response
    {
        return $this->render('pay_roll/index.html.twig', [
            'pay_rolls' => $payRollRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pay_roll_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $payRoll = new PayRoll();
        $form = $this->createForm(PayRollType::class, $payRoll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payRoll);
            $entityManager->flush();

            return $this->redirectToRoute('app_pay_roll_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pay_roll/new.html.twig', [
            'pay_roll' => $payRoll,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pay_roll_show', methods: ['GET'])]
    public function show(PayRoll $payRoll): Response
    {
        return $this->render('pay_roll/show.html.twig', [
            'pay_roll' => $payRoll,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pay_roll_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PayRoll $payRoll, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PayRollType::class, $payRoll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pay_roll_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pay_roll/edit.html.twig', [
            'pay_roll' => $payRoll,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pay_roll_delete', methods: ['POST'])]
    public function delete(Request $request, PayRoll $payRoll, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payRoll->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($payRoll);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pay_roll_index', [], Response::HTTP_SEE_OTHER);
    }
}
