<?php

namespace App\Form;

use App\Entity\ExamenOrinaMicroscopico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExamenOrinaMicroscopicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uretral',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('urotelio',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('renal',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('leucocitos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('piocitos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('eritrocitos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('bacteria',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('parasitos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('funguria',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('filamentoDeMucina',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('proteinaUromocoide',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('cilindros',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenOrinaMicroscopico::class,
        ]);
    }
}
