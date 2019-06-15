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
        date_default_timezone_set("America/El_Salvador");

        $builder
            ->add('primerNombre', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('segundoNombre', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('primerApellido', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('segundoApellido', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('fechaNacimiento', DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['class' => 'form-control', 'max' => $options["date"]->format('Y-m-d')]])
            ->add('telefono', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('descripcion', TextType::class,array('attr'=> array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        date_default_timezone_set("America/El_Salvador");

        $resolver->setDefaults([
            'data_class' => Familiar::class,
            'date' => date_add(date_create(),date_interval_create_from_date_string("-1 years"))
        ]);
    }
}
