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

#[Route('/payroll')]
final class PayRollController extends AbstractController
{
    #[Route(name: 'app_pay_roll_index', methods: ['GET'])]
    public function index(PayRollRepository $payRollRepository, EntityManagerInterface $em): Response
    {
        $items = $payRollRepository->findAll();
        $fields = $em->getClassMetadata(PayRoll::class)->getFieldNames();

        return $this->render('CRUD/list.html.twig', [
            'page_title' => 'Payroll Records',
            'items' => $items,
            'columns' => $fields,
            'entity' => 'pay_roll',
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

            return $this->redirectToRoute('app_pay_roll_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Create Payroll Record',
            'form' => $form->createView(),
            'submit_label' => 'Save', // <-- add this
            'entity' => 'pay_roll',
        ]);
    }

    #[Route('/{id}', name: 'app_pay_roll_show', methods: ['GET'])]
    public function show(PayRoll $payRoll, EntityManagerInterface $em): Response
    {
        $fields = $em->getClassMetadata(PayRoll::class)->getFieldNames();

        return $this->render('CRUD/show.html.twig', [
            'page_title' => 'Payroll Details',
            'item' => $payRoll,
            'columns' => $fields,
            'entity' => 'pay_roll',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pay_roll_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PayRoll $payRoll, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PayRollType::class, $payRoll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_pay_roll_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Edit Payroll Record',
            'form' => $form->createView(),
            'submit_label' => 'Update Payroll Record',
            'entity' => 'pay_roll',
        ]);
    }

    #[Route('/{id}', name: 'app_pay_roll_delete', methods: ['POST'])]
    public function delete(Request $request, PayRoll $payRoll, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $payRoll->getId(), $request->request->get('_token'))) {
            $entityManager->remove($payRoll);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pay_roll_index');
    }
}
