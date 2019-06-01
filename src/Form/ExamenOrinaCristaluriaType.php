<?php

namespace App\Form;

use App\Entity\ExamenOrinaCristaluria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ExamenOrinaCristaluriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uratosAmorfos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('acidoUrico',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('oxalatosCalcicos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('fosfatosAmorfos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('fosfatosCalcicos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('fosfatosAmonicos',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('riesgoLitogenico',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success','placeholder'=> 'Digite * si no desea establecer ningun dato')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenOrinaCristaluria::class,
        ]);
    }
}
