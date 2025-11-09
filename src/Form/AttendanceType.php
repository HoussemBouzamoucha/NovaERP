<?php

namespace App\Form;

use App\Entity\Attendance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkIn_at', null, [
                'widget' => 'single_text',
            ])
            ->add('checkOut_at', null, [
                'widget' => 'single_text',
            ])
            ->add('totalHours')
            ->add('date_at', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attendance::class,
        ]);
    }
}
