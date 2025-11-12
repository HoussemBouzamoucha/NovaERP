<?php

namespace App\Form;

use App\Entity\LeaveRequest;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeaveRequestType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate_at', null, [
                'widget' => 'single_text',
                'label' => 'Start Date',
            ])
            ->add('endDate_at', null, [
                'widget' => 'single_text',
                'label' => 'End Date',
            ])
            ->add('reason', TextareaType::class, [
                'label' => 'Reason for Leave',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'pending',
                    'Approved' => 'approved',
                    'Rejected' => 'rejected',
                ],
                'placeholder' => 'Select status',
            ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => fn(Users $user) => $user->getFirstName() . ' ' . $user->getLastName() . ' - ' . $user->getDepartment()?->getName(),
                'placeholder' => 'Select employee',
                'label' => 'Employee',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LeaveRequest::class,
        ]);
    }
}
