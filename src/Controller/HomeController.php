<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use App\Repository\TransactionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

	#[Route('/', name: 'app_home')]
    public function index(TransactionsRepository $transactionsRepository, ProductsRepository $productsRepository): Response
    {
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
            'controller_name' => 'HomeController',
			'totalCashAmount' => $totalCashAmount,
			'totalCardAmount' => $totalCardAmount,
			'totalChequeAmount' => $totalChequeAmount,
			'totalEncaisse' => $totalEncaisse,
			'latestProducts' => $latestProducts,
			'transactions' => $transactions,
        ]);
    }
}
