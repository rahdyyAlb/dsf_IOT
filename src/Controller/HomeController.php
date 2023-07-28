<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProductsRepository;
use App\Repository\TransactionsRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home/{id}', name: 'app_home', methods: ['GET'])]
	#[ParamConverter('user' ,  User::class)]
    public function index(TransactionsRepository $transactionsRepository, ProductsRepository $productsRepository,User $user ): Response
    {
		// Récupérer l'utilisateur actuel
		$User = $this->getUser();

		// Récupérer le total des transactions par especes
		$totalCashAmount = $transactionsRepository->getTotalCashAmount();
		// Récupérer le total des transactions par carte bancaire
		$totalCardAmount = $transactionsRepository->getTotalCardAmount();
		// Récupérer le total des transactions en chèques
		$totalChequeAmount = $transactionsRepository->getTotalChequeAmount();
		// Récupérer le total des transactions
		$totalEncaisse = $totalChequeAmount + $totalCardAmount + $totalCashAmount;
		// Récupérer les 5 derniers produits ajoutés
		$latestProducts = $productsRepository->findBy([], ['id' => 'DESC'], 5);
		// Récupérer les 5 dernières transactions avec les IDs les plus grands
		$transactions = $transactionsRepository->findBy([], ['id' => 'DESC'], 5);

		return $this->render('home/index.html.twig', [
			'controller_name' => 'DashboardController',
			'totalCashAmount' => $totalCashAmount,
			'totalCardAmount' => $totalCardAmount,
			'totalChequeAmount' => $totalChequeAmount,
			'totalEncaisse' => $totalEncaisse,
			'latestProducts' => $latestProducts,
			'transactions' => $transactions,
			'User' => $User,
		]);

	}

}
