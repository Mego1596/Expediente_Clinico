<?php

namespace App\Form;

use App\Entity\PlanTratamiento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlanTratamientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dosis',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('medicamento',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('frecuencia',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('tipoMedicamento',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PlanTratamiento::class,
        ]);
    }
}
