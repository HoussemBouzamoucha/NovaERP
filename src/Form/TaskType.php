<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
     {
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'Pending',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                    'On Hold' => 'On Hold',
                ],
                'placeholder' => 'Select status',
            ])
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Low' => 'low',
                    'Medium' => 'medium',
                    'High' => 'high',
                    'Critical' => 'critical',
                ],
                'placeholder' => 'Select priority',
            ])
            ->add('deadLine_at', null, [
                'widget' => 'single_text',
                'label' => 'Deadline',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => fn(Project $project) => $project->getTitle() . ' (' . $project->getStatus() . ')',
                'placeholder' => 'Select a project',
            ])
            ->add('assignedTo', EntityType::class, [
                'class' => Users::class,
                'choice_label' => fn(Users $user) => $user->getFirstName() . ' ' . $user->getLastName() . ' - ' . $user->getDepartment()?->getName(),
                'placeholder' => 'Assign to',
                'label' => 'Assigned To',
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
