<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Transactions;
use App\Entity\User;
use App\Repository\PanierRepository;
use App\Repository\ProductsRepository;
use App\Repository\TransactionsRepository;
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
    public function index(TransactionsRepository $transactionsRepository, PanierRepository $panierRepository, ProductsRepository $productsRepository, SessionInterface $session, User $user): Response
    {
        $User = $this->getUser();
        $id = $User->getId();
        $currentUser = $session->get('User');

        // Récupérer tous les éléments du panier associés à l'utilisateur actuel
        $panierItems = $panierRepository->findAll(); // Supposant que le champ qui relie l'utilisateur aux éléments du panier est 'user'

        $productsInPanier = [];

        foreach ($panierItems as $panierItem) {
            $product = $panierItem->getProducts();

            $productData = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'img' => $product->getImg(),
                'barcode' => $product->getBarCode(),
            ];

            $productsInPanier[] = $productData;
        }

        return $this->render('home/index.html.twig', [
            'User' => $User,
            'id' => $id,
            'panierItems' => $panierItems,
            'currentUser' => $currentUser,
            'productsInPanier' => $productsInPanier, // Passer les produits dans le panier à la vue
            'session' => $session,
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
        $panierItems = $entityManager->getRepository(Panier::class);
        foreach ($panierItems as $panierItem) {
            $product = $panierItem->getProduit();
            $transaction->addProduct($product);

            // Supprimer le produit du panier après l'ajout à la transaction
            $entityManager->remove($panierItem);
        }

        // Enregistrer la transaction
        $entityManager->persist($transaction);
        $entityManager->flush();

        // Vider la table Panier pour l'utilisateur actuel
        foreach ($panierItems as $panierItem) {
            $entityManager->remove($panierItem);
        }
        $entityManager->flush();

        $session->getFlashBag()->set('scanned_product', []);

        $id = $this->getUser()->getId();
        $this->addFlash('success', 'La transaction a été validée avec succès.');

        return $this->redirectToRoute('app_home', ['id' => $id]);
    }
}
