<?php

namespace App\Controller\UserManagement;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/department')]
final class DepartmentController extends AbstractController
{
    #[Route(name: 'app_department_index', methods: ['GET'])]
public function index(DepartmentRepository $departmentRepository): Response
{
    $departments = $departmentRepository->findAll();

    // Dynamically get all properties (columns) using Reflection
    $columns = [];
    if (!empty($departments)) {
        $reflection = new \ReflectionClass($departments[0]);
        foreach ($reflection->getProperties() as $prop) {
            $columns[] = $prop->getName();
        }
    }

    return $this->render('crud/list.html.twig', [
        'page_title' => 'Departments',
        'items' => $departments,
        'columns' => $columns,
        'entity' => 'department',
    ]);
}

    #[Route('/new', name: 'app_department_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud/form.html.twig', [
            'page_title' => 'Create Department',
            'form' => $form->createView(),
            'submit_label' => 'Save Department',
            'entity' => 'department',
        ]);
    }

    #[Route('/{id}', name: 'app_department_show', methods: ['GET'])]
    public function show(Department $department): Response
    {
        return $this->render('crud/show.html.twig', [
            'page_title' => 'Department Details',
            'item' => $department,
            'entity' => 'department',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_department_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud/form.html.twig', [
            'page_title' => 'Edit Department',
            'form' => $form->createView(),
            'submit_label' => 'Update Department',
            'entity' => 'department',
        ]);
    }

    #[Route('/{id}', name: 'app_department_delete', methods: ['POST'])]
    public function delete(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->request->get('_token'))) {
            $entityManager->remove($department);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
    }
}
