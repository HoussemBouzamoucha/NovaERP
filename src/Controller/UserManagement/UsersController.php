<?php

namespace App\Controller\UserManagement;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users')]
#[IsGranted('ROLE_ADMIN')]
final class UsersController extends AbstractController
{
    #[Route(name: 'app_users_index', methods: ['GET'])]
    public function index(UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findAll();

        // Dynamically get columns
        $columns = [];
        if (!empty($users)) {
            $reflection = new \ReflectionClass($users[0]);
            foreach ($reflection->getProperties() as $prop) {
                $columns[] = $prop->getName();
            }
        }

        return $this->render('crud/list.html.twig', [
            'page_title' => 'Users List',
            'items' => $users,
            'columns' => $columns,
            'entity' => 'users',
        ]);
    }

    #[Route('/new', name: 'app_users_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, [
            'available_roles' => $this->getParameter('app.available_roles'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud/form.html.twig', [
            'page_title' => 'Create User',
            'form' => $form->createView(),
            'submit_label' => 'Save User',
            'entity' => 'users',
        ]);
    }

    #[Route('/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('crud/show.html.twig', [
            'page_title' => 'User Details',
            'item' => $user,
            'entity' => 'users',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UsersType::class, $user, [
            'available_roles' => $this->getParameter('app.available_roles'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud/form.html.twig', [
            'page_title' => 'Edit User',
            'form' => $form->createView(),
            'submit_label' => 'Update User',
            'entity' => 'users',
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
