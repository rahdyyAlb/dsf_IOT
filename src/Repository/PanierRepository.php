<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Panier;
use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function updatePanierQuantities(EntityManagerInterface $entityManager)
    {
        $panierItems = $entityManager->getRepository(Panier::class)->findAll();

        $productQuantities = [];

        foreach ($panierItems as $panierItem) {
            $product = $panierItem->getProducts();
            $productId = $product->getId();

            // Si ce produit n'a pas encore été rencontré, initialisez la quantité à 0
            if (!isset($productQuantities[$productId])) {
                $productQuantities[$productId] = 0;
            }

            // Incrémentez la quantité pour ce produit (nombre de fois qu'il apparaît dans le panier)
            $productQuantities[$productId] += $panierItem->getQuantity();

            // Supprimez le doublon (panierItem) car il a été traité
            $entityManager->remove($panierItem);
        }

        // Mettez à jour les quantités et persistez les changements
        foreach ($productQuantities as $productId => $quantity) {
            $product = $entityManager->getRepository(Products::class)->find($productId);

            if ($product) {
                $panierItem = new Panier();

                $panierItem->setProducts($product);
                $panierItem->setQuantity($quantity);
                $entityManager->persist($panierItem);
            }
        }

        $entityManager->flush();
    }
}
