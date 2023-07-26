<?php

namespace App\Controller;

use App\Entity\TransactionIteme;
use App\Form\TransactionItemeType;
use App\Repository\TransactionItemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction/iteme')]
class TransactionItemeController extends AbstractController
{
    #[Route('/', name: 'app_transaction_iteme_index', methods: ['GET'])]
    public function index(TransactionItemeRepository $transactionItemeRepository): Response
    {
        return $this->render('transaction_iteme/index.html.twig', [
            'transaction_itemes' => $transactionItemeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_transaction_iteme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transactionIteme = new TransactionIteme();
        $form = $this->createForm(TransactionItemeType::class, $transactionIteme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transactionIteme);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_iteme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction_iteme/new.html.twig', [
            'transaction_iteme' => $transactionIteme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_iteme_show', methods: ['GET'])]
    public function show(TransactionIteme $transactionIteme): Response
    {
        return $this->render('transaction_iteme/show.html.twig', [
            'transaction_iteme' => $transactionIteme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_iteme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TransactionIteme $transactionIteme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionItemeType::class, $transactionIteme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_iteme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction_iteme/edit.html.twig', [
            'transaction_iteme' => $transactionIteme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_iteme_delete', methods: ['POST'])]
    public function delete(Request $request, TransactionIteme $transactionIteme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transactionIteme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transactionIteme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_iteme_index', [], Response::HTTP_SEE_OTHER);
    }
}
