<?php

namespace App\Controller\Logistics;

use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/supplier')]
final class SupplierController extends AbstractController
{
    #[Route(name: 'app_supplier_index', methods: ['GET'])]
    public function index(SupplierRepository $supplierRepository, EntityManagerInterface $em): Response
    {
        $items = $supplierRepository->findAll();

        // Get all entity field names dynamically
        $columns = $em->getClassMetadata(Supplier::class)->getFieldNames();

        return $this->render('CRUD/list.html.twig', [
            'page_title' => 'Suppliers',
            'items' => $items,
            'columns' => $columns,
            'entity' => 'supplier',
        ]);
    }

    #[Route('/new', name: 'app_supplier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supplier);
            $entityManager->flush();

            return $this->redirectToRoute('app_supplier_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Create Supplier',
            'form' => $form->createView(),
            'submit_label' => 'Save', // <-- add this
            'entity' => 'supplier',
        ]);
    }

    #[Route('/{id}', name: 'app_supplier_show', methods: ['GET'])]
    public function show(Supplier $supplier, EntityManagerInterface $em): Response
    {
        $columns = $em->getClassMetadata(Supplier::class)->getFieldNames();

        return $this->render('CRUD/show.html.twig', [
            'page_title' => 'Supplier Details',
            'item' => $supplier,
            'columns' => $columns,
            'entity' => 'supplier',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supplier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supplier $supplier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_supplier_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Edit Supplier',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_supplier_delete', methods: ['POST'])]
    public function delete(Request $request, Supplier $supplier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supplier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($supplier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_supplier_index');
    }
}
