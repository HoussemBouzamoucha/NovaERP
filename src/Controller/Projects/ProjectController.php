<?php

namespace App\Controller\Projects;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    #[Route(name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository, EntityManagerInterface $em): Response
    {
        $items = $projectRepository->findAll();
        $columns = $em->getClassMetadata(Project::class)->getFieldNames();

        return $this->render('CRUD/list.html.twig', [
            'page_title' => 'Projects',
            'items' => $items,
            'columns' => $columns,
            'entity' => 'project',
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Create Project',
            'form' => $form->createView(),
            'submit_label' => 'Save', // <-- add this
            'entity' => 'project',

        ]);
    }

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project, EntityManagerInterface $em): Response
    {
        $columns = $em->getClassMetadata(Project::class)->getFieldNames();

        return $this->render('CRUD/show.html.twig', [
            'page_title' => 'Project Details',
            'item' => $project,
            'columns' => $columns,
            'entity' => 'project',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_project_index');
        }

        return $this->render('CRUD/form.html.twig', [
            'page_title' => 'Edit Project',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_project_index');
    }
}
