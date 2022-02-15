<?php

namespace App\Controller;

use App\Entity\Investor;
use App\Form\InvestorType;
use App\Repository\InvestorRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InvestorController extends AbstractController
{
    public function __construct(InvestorRepository $investorRepository)
    {
        $this->investorRepository = $investorRepository;
    }

    /**
     * @Route("/investor/create", name="investor")
     */
    public function create(Request $request): Response
    {
        $investor = new Investor();
        $form = $this->createForm(InvestorType::class, $investor);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->investorRepository->addInvestor($investor);
            $this->addFlash('success', 'Investor has been created');
            return $this->redirectToRoute('loan_dashboard');
        }
        return $this->render('investor/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/investor/view/{id}", name= "investor_view")
     */
    public function view($id)
    {
        $loan = $this->investorRepository->findRelationData($id);
        return $this->render('investor/view.html.twig', array('loan' => $loan));
    }
}
