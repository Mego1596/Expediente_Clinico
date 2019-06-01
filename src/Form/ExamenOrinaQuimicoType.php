<?php

namespace App\Form;

use App\Entity\ExamenOrinaQuimico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class ExamenOrinaQuimicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('densidad', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('ph', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('glucosa',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('proteinas',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('hemoglobina',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('cuerpoCetonico',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('pigmentoBiliar',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('urobilinogeno',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('nitritos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenOrinaQuimico::class,
        ]);
    }
}
