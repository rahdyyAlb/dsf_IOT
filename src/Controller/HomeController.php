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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home/{id}', name: 'app_home', methods: ['GET'])]
    #[ParamConverter('user', User::class)]
    public function index(TransactionsRepository $transactionsRepository, ProductsRepository $productsRepository, SessionInterface $session, User $user): Response
    {
        $scannedProducts = [];
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
        $barcodes = [3228857000906, 8076800195057, 3045140105502];
        $productData = [];
        $httpClient = HttpClient::create();
        foreach ($barcodes as $barcode) {
            $response = $httpClient->request('GET', 'http://localhost:8000/api/products/'.$barcode);
            $productData[] = $response->toArray();
        }
        $session->getFlashBag()->add('scanned_product', $productData);

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
            'productData' => $productData,
            'scannedProducts' => $scannedProducts,
            'session' => $session,
        ]);
    }

    #[Route('/api/products/{barcode}', methods: ['GET'])]
    public function getProductByBarcode($barcode, ProductsRepository $productsRepository, SessionInterface $session)
    {
        $product = $productsRepository->findOneBy(['barCode' => $barcode]);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        // Convertir l'objet Product en un tableau pour le renvoyer en JSON
        $productData = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'img' => $product->getImg(),
            'barcode' => $product->getBarCode(),
        ];

        return $this->json($productData);
    }

    #[Route('/api/validate-transaction', name: 'app_validate_transaction', methods: ['POST'])]
    public function validateTransaction(EntityManagerInterface $entityManager, ProductsRepository $productsRepository, SessionInterface $session)
    {
        $user = $this->getUser();
        $scannedProducts = $session->getFlashBag()->get('scanned_product', []);

        // Récupérer l'instance de la caisse associée à l'utilisateur
        $caisse = $user->getCaisse();

        $transaction = new Transactions();

        $transaction->setTransactionsDate(new \DateTime('now'));
        $transaction->updateTotalAmount();
        $transaction->setCaisse($caisse);
        // Ajout des produits scannés à la transaction
        foreach ($scannedProducts as $scannedProductArray) {
            foreach ($scannedProductArray as $scannedProduct) {
                $barcode = $scannedProduct['barcode'];

                // Recherche du produit dans la base de données par code-barres
                $product = $productsRepository->findOneBy(['barCode' => $barcode]);

                if ($product) {
                    $transaction->addProduct($product);
                }
            }
        }
        $entityManager->persist($transaction);
        $entityManager->flush();

        $session->getFlashBag()->set('scanned_product', []);

        $id = $this->getUser()->getId();
        $this->addFlash('success', 'La transaction a été validée avec succès.');

        return $this->redirectToRoute('app_home', ['id' => $id]);
    }
}
