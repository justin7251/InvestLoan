<?php

namespace App\Form;

use App\Entity\Loan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;

use App\Validator\InvestGreaterthanTracheAmount;

class InvestLoanType extends AbstractType
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('investor_id', ChoiceType::class, [
                'choices'  => $options['investor_options'],
            ])
            ->add('tranche_id', ChoiceType::class, [
                'choices'  => $options['tranche_options']])
            ->add('invest_amount', NumberType::class, [
                'constraints' => [
                    // unable to get the submit value to compare
                    // new InvestGreaterthanTracheAmount([])
                ]
            ])
            ->add('start_date', DateType::class)
            ->add('end_date', DateType::class, [
                'constraints' => [
                    new GreaterThan([
                        'propertyPath' => 'parent.all[start_date].data'
                    ])
                ]
            ]);

        $builder
            ->get('investor_id')
            ->addModelTransformer(new CallbackTransformer(
            function ($integer) {
                // transform the integer back to string
                return (string) $integer;
            },
            function ($string) {
                // transform the string to a integer
                return (int) $string;
            }
            ));

        $builder
            ->get('tranche_id')
            ->addModelTransformer(new CallbackTransformer(
            function ($integer) {
                // transform the integer back to string
                return (string) $integer;
            },
            function ($string) {
                // transform the string to a integer
                return (int) $string;
            }
            ));
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'investor_options' => array(),
            'tranche_options' => array(),
        ]);
    }
}
