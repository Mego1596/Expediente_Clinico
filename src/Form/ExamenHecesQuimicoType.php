<?php

namespace App\Form;

use App\Entity\ExamenHecesQuimico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenHecesQuimicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ph',TextType::class, array('attr' => array('class' => 'form-control','placeholder'=> 'Digite * si no desea establecer ningun dato')))
            ->add('azucares_reductores',TextType::class, array('attr' => array('class' => 'form-control','placeholder'=> 'Digite * si no desea establecer ningun dato')))
            ->add('sangre_oculta',TextType::class, array('attr' => array('class' => 'form-control','placeholder'=> 'Digite * si no desea establecer ningun dato')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHecesQuimico::class,
        ]);
    }
}
