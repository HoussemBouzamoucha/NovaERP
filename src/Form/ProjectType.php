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
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('startDate_at', null, [
                'widget' => 'single_text',
                'label' => 'Start Date',
            ])
            ->add('endDate_at', null, [
                'widget' => 'single_text',
                'label' => 'End Date',
            ])
            ->add('budget', MoneyType::class, [
                'currency' => 'USD',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Project Status',
                'choices' => [
                    'Pending' => Project::STATUS_PENDING,
                    'In Progress' => Project::STATUS_IN_PROGRESS,
                    'Completed' => Project::STATUS_COMPLETED,
                ],
                'expanded' => false,  // dropdown instead of checkboxes
                'multiple' => false,  // single selection
                'placeholder' => 'Select status',
            ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => fn(Users $user) => $user->getFirstName() . ' ' . $user->getLastName() . ' - ' . implode(', ', $user->getRoles()),
                'multiple' => true,
                'label' => 'Team Members',
                
                'required' => false,
            ])
            ->add('Client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => fn(Client $client) => $client->getName(),
                'placeholder' => 'Select a client',
            ])
            ->add('inventories', EntityType::class, [
                'class' => Inventory::class,
                'choice_label' => fn(Inventory $inventory) => $inventory->getItemName() . ' (SKU: ' . $inventory->getSku() . ') - Qty: ' . $inventory->getQuantity(),
                'multiple' => true,
                'label' => 'Assigned Inventory',
                'placeholder' => 'Select inverytory items',

                'required' => false,
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
