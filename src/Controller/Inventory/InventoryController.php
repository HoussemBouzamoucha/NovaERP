<?php

namespace App\Controller\Inventory;

use App\Entity\Inventory;
use App\Form\InventoryType;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/inventory')]
final class InventoryController extends AbstractController
{
    #[Route(name: 'app_inventory_index', methods: ['GET'])]
    public function index(InventoryRepository $inventoryRepository, EntityManagerInterface $em): Response
{
    $items = $inventoryRepository->findAll();

    // Get all entity field names dynamically
    $columns = $em->getClassMetadata(Inventory::class)->getFieldNames();

    return $this->render('CRUD/list.html.twig', [
        'page_title' => 'Inventory Items',
        'items' => $items,
        'columns' => $columns,   // must match Twig variable name
        'entity' => 'inventory',  // for generating routes in Twig
    ]);
}

    #[Route('/new', name: 'app_inventory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $inventory = new Inventory();
        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inventory);
            $entityManager->flush();

            return $this->redirectToRoute('app_inventory_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Create Inventory Item',
            'form' => $form->createView(),
            'submit_label' => 'Save', 
            'entity' => 'inventory',
        ]);
    }

    #[Route('/{id}', name: 'app_inventory_show', methods: ['GET'])]
    public function show(Inventory $inventory, EntityManagerInterface $em): Response
    {
        $columns = $em->getClassMetadata(Inventory::class)->getFieldNames();

        return $this->render('CRUD/show.html.twig', [
            'page_title' => 'Inventory Details',
            'item' => $inventory,
            'fields' => $columns, // dynamic columns
            'entity' => 'inventory',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inventory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inventory $inventory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InventoryType::class, $inventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_inventory_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Edit Inventory Item',
            'form' => $form->createView(),
            'submit_label' => 'Update Inventory Item',
            'entity' => 'inventory',
        ]);
    }

    #[Route('/{id}', name: 'app_inventory_delete', methods: ['POST'])]
    public function delete(Request $request, Inventory $inventory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $inventory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($inventory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_inventory_index');
    }
}
