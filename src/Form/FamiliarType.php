<?php

namespace App\Form;

use App\Entity\Familiar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FamiliarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombres', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('apellidos', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('fechaNacimiento', DateType::class,['widget' => 'single_text','html5' => true,'attr' => ['class' => 'form-control']])
            ->add('telefono', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('descripcion', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Familiar::class,
        ]);
    }
}
