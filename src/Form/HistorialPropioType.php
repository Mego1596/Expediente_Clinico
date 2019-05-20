<?php

namespace App\Form;

use App\Entity\HistorialPropio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class HistorialPropioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', TextType::class,array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HistorialPropio::class,
        ]);
    }
}
