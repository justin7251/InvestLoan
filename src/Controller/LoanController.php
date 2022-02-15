<?php

namespace App\Controller;

use App\Entity\Investor;
use App\Entity\Tranche;
use App\Entity\Loan;

use App\Form\InvestLoanType;
use App\Repository\LoanRepository;
use App\Repository\InvestorRepository;
use App\Repository\TrancheRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoanController extends AbstractController
{
    private $manager;
    
    public function __construct(
        EntityManagerInterface $manager,
        LoanRepository $loanRepository,
        InvestorRepository $investorRepository,
        TrancheRepository $trancheRepository
    )
    {
        $this->manager = $manager;
        $this->loanRepository = $loanRepository;
        $this->investorRepository = $investorRepository;
        $this->trancheRepository = $trancheRepository;
    }

    /**
     * @Route("/", name="loan_dashboard")
     */
    public function index(): Response
    {
        $investors = $this->manager->getRepository(Investor::class)->findAll();
        $tranches = $this->manager->getRepository(Tranche::class)->findAll();
        return $this->render('loan/index.html.twig', [
            'investors' => $investors,
            'count_of_investor' => count($investors),
            'count_of_tranche' => count($tranches),
            'tranches' => $tranches
        ]);
    }

    /**
     * @Route("/loan/invest_loan", name="invest_loan")
     */
    public function invest_loan(Request $request): Response
    {
        $form_options = $this->getInvestorTrancheOptions();
        // if there isn't any option
        if (count($form_options['investor_options']) < 1 || count($form_options['tranche_options']) < 1) {
            $this->addFlash('warning', 'Sorry, we are currently unable to allow investments. Please add at least one Investor and Tranche.');
            return $this->redirectToRoute('loan_dashboard');
        }
        $loan = new Loan();
        $form = $this->createForm(InvestLoanType::class, $loan, $form_options);
        $render_option['errors'] = array();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $invest = $loan->getInvestAmount();
            $InvestorId = $loan->getInvestorId();
            $TrancheId = $loan->getTrancheId();
            $wallet_amount = $this->investorRepository->findById($InvestorId);
            $available_allowance = $this->trancheRepository->findAvailableAllowance($TrancheId);
            $this->customValidation($invest, $wallet_amount, $available_allowance, $render_option);
            if (count($render_option['errors']) < 1) {
                $this->loanRepository->addLoan($loan);
                $this->investorRepository->reduceWalletAmount($InvestorId, ($wallet_amount - $invest));
                $this->trancheRepository->addCurrentUsage($TrancheId, $invest);
                $this->addFlash('success', 'Loan has been created');
                return $this->redirectToRoute('loan_dashboard');
            }
        }
        $render_option['form'] = $form->createView();
        return $this->render('loan/invest_loan.html.twig', $render_option);
    }

    /**
     * Unable to understand symfony validation therefore I have create my own UI validation
     */
    private function customValidation($invest, $wallet_amount, $max_amount, &$render_option)
    {
        if ($invest > $wallet_amount) {
            $render_option['errors'][] = 'The investment amount is greater than the wallet amount. Your wallet has £' . $wallet_amount;
        }
        if ($invest > $max_amount) {
            $render_option['errors'][] = 'The investment amount is greater than the trache limit. The trache allowance is ' . $max_amount;
        }
    }

    private function getInvestorTrancheOptions()
    {
        $investor_options = $this->manager->getRepository(Investor::class)->findInvestorIdAndName();
        $tranche_options = $this->manager->getRepository(Tranche::class)->findTrancheIdAndName();
        return array('investor_options' => $investor_options, 'tranche_options' => $tranche_options);
    }
}
