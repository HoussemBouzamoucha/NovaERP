<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Invoice;
use App\Entity\Project;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('invoiceNumber')
            ->add('issueDate_at', null, [
                'widget' => 'single_text',
            ])
            ->add('dueDate_at', null, [
                'widget' => 'single_text',
            ])
            ->add('totalAmount')
            ->add('status')
            ->add('author', EntityType::class, [
                'class' => Users::class,
                'choice_label' => fn(Users $user) => $user->getFirstName() . ' ' . $user->getLastName(),
                'placeholder' => 'Select author',
            ])
            ->add('Client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => fn(Client $client) => $client->getName() . ' - ' . $client->getEmail(),
                'placeholder' => 'Select a client',
            ])
            ->add('Project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => fn(Project $project) => $project->getTitle() . ' (Budget: $' . number_format($project->getBudget(), 2) . ')',
                'placeholder' => 'Select a project',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
