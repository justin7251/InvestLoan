<?php

namespace App\Controller;

use App\Entity\Tranche;
use App\Form\TrancheType;
use App\Repository\TrancheRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrancheController extends AbstractController
{
    public function __construct(TrancheRepository $trancheRepository)
    {
        $this->trancheRepository = $trancheRepository;
    }

    /**
     * @Route("/tranche/create", name="tranche")
     */
    public function create(Request $request): Response
    {
        $tranche = new Tranche();
        $form = $this->createForm(TrancheType::class, $tranche);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->trancheRepository->addTranche($tranche);
            $this->addFlash('success', 'Tranche has been created.');
            return $this->redirectToRoute('loan_dashboard');
        }
        return $this->render('tranche/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
