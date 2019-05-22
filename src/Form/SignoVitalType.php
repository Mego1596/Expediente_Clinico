<?php

namespace App\Form;

use App\Entity\SignoVital;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class SignoVitalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('peso', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('temperatura', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('estatura', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('presionArterial', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('ritmoCardiaco', NumberType::class, array('attr' => array('class' => 'form-control' , 'step' => '0.01', 'min' => '0.01' )))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SignoVital::class,
        ]);
    }
}
