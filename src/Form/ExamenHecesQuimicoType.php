<?php

namespace App\Form;

use App\Entity\ExamenHecesQuimico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenHecesQuimicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ph', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('azucares_reductores',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('sangre_oculta',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHecesQuimico::class,
        ]);
    }
}
