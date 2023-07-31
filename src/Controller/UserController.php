<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/')]
class UserController extends AbstractController
{
	#[Route(path: '/', name: 'app_login')]
	public function login (AuthenticationUtils $authenticationUtils): Response
	{


		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		// Check if the user is authenticated and has the ROLE_ADMIN
		if ($this->getUser() && $this->isGranted('ROLE_ADMIN')) {
			// If the user is an admin, redirect to the admin route
			return $this->redirectToRoute('admin');
		}

		return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
	}

	#[Route(path: '/logout', name: 'app_logout')]
	public function logout (): void
	{
		throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
	}

	#[Route('/alluser', name: 'app_user_index', methods: ['GET'])]
	public function index (UserRepository $userRepository): Response
	{
		$user = $this->getUser();
		$id = $user->getId();
		return $this->render('user/index.html.twig', [
			'users' => $userRepository->findAll(),
			'user' => $user,
			'id' => $id,
		]);
	}

	#[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
	public function new (Request $request, EntityManagerInterface $entityManager): Response
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->render('user/new.html.twig', [
			'user' => $user,
			'form' => $form,
		]);
	}

	#[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
	public function show (User $user): Response
	{
		return $this->render('user/show.html.twig', [
			'user' => $user,
		]);
	}

	#[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
	public function edit (Request $request, User $user, EntityManagerInterface $entityManager): Response
	{
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->render('user/edit.html.twig', [
			'user' => $user,
			'form' => $form,
		]);
	}

	#[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
	public function delete (Request $request, User $user, EntityManagerInterface $entityManager): Response
	{
		if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
			$entityManager->remove($user);
			$entityManager->flush();
		}

		return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
	}
}
