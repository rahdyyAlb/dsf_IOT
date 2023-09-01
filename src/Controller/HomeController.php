<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Transactions;
use App\Entity\TransactionsProducts;
use App\Entity\User;
use App\Repository\PanierRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home/{id}', name: 'app_home', methods: ['GET'])]
    #[ParamConverter('user', User::class)]
    public function index(EntityManagerInterface $entityManager, PanierRepository $panierRepository, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $currentUser = $session->get('User');
        $panierRepository->updatePanierQuantities($entityManager);
        $productsInPaniers = [];

        // Récupérer tous les éléments du panier associés à l'utilisateur actuel
        $panierItems = $panierRepository->findAll(); // Supposant que le champ qui relie l'utilisateur aux éléments du panier est 'user'

        $cartTotal = 0.0; // Initialiser le prix total du panier

        foreach ($panierItems as $panierItem) {
            $product = $panierItem->getProducts();

            $productData = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getUnitPrice(),
                'img' => $product->getImg(),
                'barcode' => $product->getBarCode(),
            ];

            $productsInPaniers[] = $productData;

            // Ajouter le prix total de cet article au prix total du panier
            $cartTotal += $productData['price'] * $panierItem->getQuantity();
        }

        return $this->render('home/index.html.twig', [
            'User' => $user,
            'id' => $id,
            'panierItems' => $panierItems,
            'currentUser' => $currentUser,
            'productsInPanier' => $productsInPaniers,
            'session' => $session,
            'cartTotal' => $cartTotal, // Passer le prix total du panier à la vue
        ]);
    }

    #[Route('/api/products/{barcode}', methods: ['GET'])]
    public function getProductByBarcode($barcode, ProductsRepository $productsRepository, SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $product = $productsRepository->findOneBy(['barCode' => $barcode]);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        // Créer un nouvel élément de panier et le configurer
        $panierItem = new Panier();
        $panierItem->setUser($user); // Associer l'utilisateur à l'élément du panier
        $panierItem->setProducts($product); // Associer le produit à l'élément du panier

        // Persistez l'élément du panier et enregistrez-le dans la base de données
        $entityManager->persist($panierItem);
        $entityManager->flush();

        // Convertir l'objet Product en un tableau pour le renvoyer en JSON
        $productData = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getUnitPrice(),
            'img' => $product->getImg(),
            'barcode' => $product->getBarCode(),
        ];

        return $this->json($productData);
    }

    #[Route('/api/validate-transaction', name: 'app_validate_transaction', methods: ['POST'])]
    public function validateTransaction(EntityManagerInterface $entityManager, PanierRepository $panierRepository, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $scannedProducts = $session->getFlashBag()->get('scanned_product', []);

        // Récupérer l'instance de la caisse associée à l'utilisateur
        $caisse = $user->getCaisse();

        // Créer une nouvelle transaction
        $transaction = new Transactions();
        $transaction->setTransactionsDate(new \DateTime('now'));
        $transaction->setCaisse($caisse);

        // Récupérer les éléments du panier associés à l'utilisateur
        $panierItems = $panierRepository->findAll();

        $totalAmount = 0; // Initialiser le montant total

        foreach ($panierItems as $panierItem) {
            $product = $panierItem->getProducts();
            $productPrice = $product->getUnitPrice();
            $quantity = $panierItem->getQuantity();
            $itemTotal = $productPrice * $quantity; // Calculer le montant total pour cet article

            $totalAmount += $itemTotal; // Ajouter le montant total de cet article au montant total de la transaction

            // Ajouter le produit à la transaction
            $transactionProduct = new TransactionsProducts();
            $transactionProduct->setProduct($product);
            $transactionProduct->setTransaction($transaction);
            $transactionProduct->setQuantity($quantity);
            $transactionProduct->setPrice($itemTotal);

            $entityManager->persist($transactionProduct);

            // Supprimer l'élément du panier
            $entityManager->remove($panierItem);
        }

        // Définir le montant total de la transaction
        $transaction->setTotalAmount($totalAmount);

        // Enregistrer la transaction et vider le panier
        $entityManager->persist($transaction);
        $entityManager->flush();

        $session->getFlashBag()->set('scanned_product', []);

        $id = $user->getId();
        $this->addFlash('success', 'La transaction a été validée avec succès.');

        return $this->redirectToRoute('app_home', ['id' => $id]);
    }
}
