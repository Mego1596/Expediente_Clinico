<?php

namespace App\Form;

use App\Entity\Cita;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaReservacion',DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['class' => 'form-control']])
            ->add('consultaPor',TextType::class, array('attr' => array('class' => 'form-control','name'=>'consultaPor')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cita::class,
        ]);
    }
}
