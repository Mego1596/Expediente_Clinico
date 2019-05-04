<?php

namespace App\Form;

use App\Entity\Clinica;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ClinicaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreClinica',TextType::class, array('attr'=> array('class'=> 'form-control')))
            ->add('direccion',TextType::class, array('attr'=> array('class'=> 'form-control')))
            ->add('telefono',TextType::class, array('attr'=> array('class'=> 'form-control')))
            ->add('email',EmailType::class, array('attr'=> array('class'=> 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clinica::class,
        ]);
    }
}
