<?php

namespace App\Form;

use App\Entity\ExamenQuimicaSanguinea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExamenQuimicaSanguineaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parametro',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('resultado',IntegerType::class, array('attr' => array('class' => 'form-control' , 'step' => '1', 'min' => '0' )))
            ->add('comentario',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('unidades',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('rango',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenQuimicaSanguinea::class,
        ]);
    }
}
