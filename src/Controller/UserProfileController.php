<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
	#[Route('/user/profile/{id}', name: 'profil_home')]
	public function index($id): Response
	{
		return $this->render('user_profile/index.html.twig', [
			'controller_name' => 'UserProfileController',
			'user_id' => $id,
		]);
	}
}
