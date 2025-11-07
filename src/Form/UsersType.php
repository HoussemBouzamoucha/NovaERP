<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class UsersType extends AbstractType
{
    public function __construct(private ParameterBagInterface $params) {}


     public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $availableRoles = $this->params->get('app.available_roles');

        $choices = [];
        foreach ($availableRoles as $role) {
            $choices[ucfirst(strtolower(str_replace('ROLE_', '', $role)))] = $role;
        }

       $builder
    ->add('email')
    ->add('password')
    ->add('roles', ChoiceType::class, [
        'choices'  => array_combine($options['available_roles'], $options['available_roles']),
        'multiple' => true,       // allow selecting multiple roles
        'expanded' => false,      // dropdown (true would be checkboxes)
        'label'    => 'Roles',
    ])
    ->add('firstName')
    ->add('lastName')
    ->add('department');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'available_roles' => [],
        ]);
    }
}
