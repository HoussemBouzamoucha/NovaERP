<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Inventory;
use App\Entity\Project;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('status', ChoiceType::class, [
                'label' => 'Project Status',
                'choices' => [
                    'Pending' => Project::STATUS_PENDING,
                    'In Progress' => Project::STATUS_IN_PROGRESS,
                    'Completed' => Project::STATUS_COMPLETED,
                ],
                'expanded' => true,  // shows checkboxes
                'multiple' => true,  // allows multiple selections
            ])

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
