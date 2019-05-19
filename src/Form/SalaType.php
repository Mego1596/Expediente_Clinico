<?php

namespace App\Form;

use App\Entity\Sala;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SalaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreSala',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sala::class,
        ]);
    }
}
