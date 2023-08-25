<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Transactions;
use App\Entity\User;
use App\Repository\ProductsRepository;
use App\Repository\TransactionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $scannedProducts = [];

    #[Route('/home/{id}', name: 'app_home', methods: ['GET'])]
    #[ParamConverter('user', User::class)]
    public function index(TransactionsRepository $transactionsRepository, ProductsRepository $productsRepository, User $user): Response
    {
        // Récupérer l'utilisateur actuel
        $User = $this->getUser();
        $id = $User->getId();
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
        $barcode = 8076800195057;
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'http://localhost:8000/api/products/7622201809638');
        $productData = $response->toArray();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'DashboardController',
            'totalCashAmount' => $totalCashAmount,
            'totalCardAmount' => $totalCardAmount,
            'totalChequeAmount' => $totalChequeAmount,
            'totalEncaisse' => $totalEncaisse,
            'latestProducts' => $latestProducts,
            'transactions' => $transactions,
            'User' => $User,
            'id' => $id,
            'productData' => $productData, // Ajoutez les données du produit au tableau
        ]);
    }

    #[Route('/api/products/{barcode}', methods: ['GET'])]
    public function getProductByBarcode($barcode, ProductsRepository $productsRepository)
    {
        $product = $productsRepository->findOneBy(['barCode' => $barcode]);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }
        $this->scannedProducts[] = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'img' => $product->getImg(),
        ];

        // Convertir l'objet Product en un tableau pour le renvoyer en JSON
        $productData = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'img' => $product->getImg(),
        ];

        return $this->json($productData);
    }

    #[Route('/api/validate-transaction', name: 'app_validate_transaction', methods: ['POST'])]
    public function validateTransaction(EntityManagerInterface $entityManager)
    {
        $transaction = new Transactions();
        foreach ($this->scannedProducts as $product) {
            $transaction->addProduct($product);
        }
        $transaction->setTransactionsDate(new \DateTime('now'));
        $transaction->updateTotalAmount();

        $entityManager->persist($transaction);
        $entityManager->flush();

        $this->scannedProducts = [];

        $id = $this->getUser()->getId();

        $url = $this->generateUrl('app_home', ['id' => $id]);

        return new RedirectResponse($url);
    }
}
