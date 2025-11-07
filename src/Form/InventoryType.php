<?php

namespace App\Form;

use App\Entity\Inventory;
use App\Entity\Project;
use App\Entity\Supplier;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('itemName')
            ->add('SKU')
            ->add('quantity')
            ->add('price')
            ->add('SupplierName')
            ->add('lastUpdated_at', null, [
                'widget' => 'single_text',
            ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('Supplier', EntityType::class, [
                'class' => Supplier::class,
                'choice_label' => 'id',
            ])
            ->add('Project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inventory::class,
        ]);
    }
}
