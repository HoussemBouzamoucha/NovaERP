<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Inventory;
use App\Entity\Project;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('startDate_at', null, [
                'widget' => 'single_text',
            ])
            ->add('endDate_at', null, [
                'widget' => 'single_text',
            ])
            ->add('budget')
            ->add('status')
            ->add('yes', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('Client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
            ])
            ->add('inventories', EntityType::class, [
                'class' => Inventory::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
