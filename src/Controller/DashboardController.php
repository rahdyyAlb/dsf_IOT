<?php

namespace App\Controller;

use App\Repository\CaisseRepository;
use App\Repository\ProductsRepository;
use App\Repository\TransactionsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(TransactionsRepository $transactionsRepository, ProductsRepository $productsRepository, CaisseRepository $caisseRepository): Response
    {
        $user = $this->getUser();
        $id = $user->getId();

        // Récupérer le total des transactions par especes
        $totalCashAmount = $transactionsRepository->getTotalCashAmount();
        // Récupérer le total des transactions par carte bancaire
        $totalCardAmount = $transactionsRepository->getTotalCardAmount();
        // Récupérer le total des transactions en chèques
        $totalChequeAmount = $transactionsRepository->getTotalChequeAmount();
        // Récupérer le total des transactions
        $totalEncaisse = $transactionsRepository->getTotalAmount();
        // Récupérer les 5 derniers produits ajoutés
        $latestProducts = $productsRepository->findBy([], ['id' => 'DESC'], 5);
        // Récupérer les 5 dernières transactions avec les IDs les plus grands
        $transactions = $transactionsRepository->findBy([], ['id' => 'DESC'], 5);

        $caisses = $caisseRepository->findAll();

        $lastTransactionsByCaisse =[];
        foreach ($caisses as $caisse) {
            $caisseId = $caisse->getId();
            $lastTransaction = $transactionsRepository->getLastTransactionForCaisse($caisseId);
            $lastTransactionsByCaisse[$caisseId] = $lastTransaction;
        }
        // Récupérer le total des transactions pour chaque caisse
        $totalAmountsByCaisse = [];
        foreach ($caisses as $caisse) {
            $caisseId = $caisse->getId();
            $totalAmountsByCaisse[$caisseId] = $transactionsRepository->getTotalAmountForCaisse($caisseId);
        }

        // Vous pouvez maintenant accéder aux détails de la dernière transaction, par exemple :
        $lastTransactionTotalAmount = $lastTransaction->getTotalAmount();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'totalCashAmount' => $totalCashAmount,
            'totalCardAmount' => $totalCardAmount,
            'totalChequeAmount' => $totalChequeAmount,
            'totalEncaisse' => $totalEncaisse,
            'latestProducts' => $latestProducts,
            'transactions' => $transactions,
            'user' => $user,
            'id' => $id,
            'totalAmountsByCaisse' => $totalAmountsByCaisse,
            'caisses' => $caisses,
            'caisseRepository' => $caisseRepository,
            'lastTransaction' => $lastTransaction,
            'lastTransactionTotalAmount' =>$lastTransactionTotalAmount,
            'lastTransactionsByCaisse' => $lastTransactionsByCaisse,
        ]);
    }
}
