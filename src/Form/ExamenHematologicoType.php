<?php

namespace App\Form;

use App\Entity\ExamenHematologico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExamenHematologicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoSerie',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('unidad',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('valorReferencia',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHematologico::class,
        ]);
    }
}
