<?php

namespace App\Form;

use App\Entity\ExamenHecesMicroscopico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExamenHecesMicroscopicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hematies',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('leucocito',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('floraBacteriana',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('levadura',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHecesMicroscopico::class,
        ]);
    }
}
