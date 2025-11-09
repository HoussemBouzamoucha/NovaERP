<?php

namespace App\Form;

use App\Entity\PayRoll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayRollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('baseSalary')
            ->add('bonus')
            ->add('deduction')
            ->add('month')
            ->add('paymentDate_at', null, [
                'widget' => 'single_text',
            ])
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PayRoll::class,
        ]);
    }
}
