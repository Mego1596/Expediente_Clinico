<?php

namespace App\Form;

use App\Entity\ExamenHecesMacroscopico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenHecesMacroscopicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aspecto',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('consistencia',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('color',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('olor',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('presencia_de_sangre',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('restos_alimenticios',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('presencia_moco',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar',SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHecesMacroscopico::class,
        ]);
    }
}
