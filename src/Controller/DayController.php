<?php

namespace App\Controller;

use App\Entity\Day;
use App\Form\DayType;
use App\Repository\DayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/day')]
class DayController extends AbstractController
{
    #[Route('/', name: 'app_day_index', methods: ['GET'])]
    public function index(DayRepository $dayRepository): Response
    {
		$user = $this->getUser();
		$id = $user->getId();

        return $this->render('day/index.html.twig', [
            'days' => $dayRepository->findAll(),
			'user' => $user,
			'id' => $id,
        ]);
    }

    #[Route('/new', name: 'app_day_new', methods: ['GET', 'POST'])]
	#[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $day = new Day();
        $form = $this->createForm(DayType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($day);
            $entityManager->flush();

            return $this->redirectToRoute('app_day_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('day/new.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_day_show', methods: ['GET'])]
    public function show(Day $day): Response
    {
        return $this->render('day/show.html.twig', [
            'day' => $day,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_day_edit', methods: ['GET', 'POST'])]
	#[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Day $day, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DayType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_day_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('day/edit.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_day_delete', methods: ['POST'])]
	#[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Day $day, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$day->getId(), $request->request->get('_token'))) {
            $entityManager->remove($day);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_day_index', [], Response::HTTP_SEE_OTHER);
    }
}
