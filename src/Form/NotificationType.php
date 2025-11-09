<?php

namespace App\Form;

use App\Entity\Notification;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('message')
            ->add('isRead')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('receiver', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('sender', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
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
