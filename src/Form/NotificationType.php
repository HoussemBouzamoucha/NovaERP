<?php

namespace App\Form;

use App\Entity\Notification;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
     {
        $builder
            ->add('title')
            ->add('message', TextareaType::class)
            ->add('isRead', CheckboxType::class, [
                'label' => 'Mark as Read',
                'required' => false,
            ])
            ->add('created_at', null, [
                'widget' => 'single_text',
                'label' => 'Created At',
                'disabled' => true,
            ])
            ->add('receiver', EntityType::class, [
                'class' => Users::class,
                'choice_label' => fn(Users $user) => $user->getFirstName() . ' ' . $user->getLastName() . ' (' . $user->getEmail() . ')',
                'placeholder' => 'Select receiver',
            ])
            ->add('sender', EntityType::class, [
                'class' => Users::class,
                'choice_label' => fn(Users $user) => $user->getFirstName() . ' ' . $user->getLastName(),
                'placeholder' => 'Select sender',
                'required' => false,
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}
