<?php

namespace App\EventListener;

use App\Entity\Day;
use App\Entity\Transaction;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TransactionListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // On vérifie si l'entité ajoutée est une Transaction
        if (!$entity instanceof Transaction) {
            return;
        }

        $entityManager = $args->getObjectManager();
        $caisse = $entity->getCaisse();

        // On récupère la journée courante de la caisse
        $currentDate = new \DateTime('today');
        $day = $entityManager->getRepository(Day::class)->findOneBy([
            'caisse' => $caisse,
            'date' => $currentDate,
        ]);

        // Si la journée n'existe pas encore, on la crée
        if (!$day) {
            $day = new Day();
            $day->setDate($currentDate);
            $day->setCaisse($caisse);
            $entityManager->persist($day);
        }

        // Mettez à jour les totaux en fonction du type de paiement de la transaction
        $paymentType = $entity->getPaymentType();
        $amount = $entity->getTotalAmount();

        switch ($paymentType) {
            case 'cash':
                $day->setCashTotal($day->getCashTotal() + $amount);
                break;
            case 'card':
                $day->setCardTotal($day->getCardTotal() + $amount);
                break;
            case 'cheque':
                $day->setChequeTotal($day->getChequeTotal() + $amount);
                break;
                // Ajoutez d'autres cas si nécessaire pour d'autres modes de paiement
        }

        $entityManager->flush();
    }
}
