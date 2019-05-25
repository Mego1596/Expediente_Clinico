<?php

namespace App\Form;

use App\Entity\HistoriaMedica;
use App\Entity\Diagnostico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class HistoriaMedicaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consultaPor',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('signos',TextareaType::class, array('attr' => array('class' => 'form-control', 'rows' => '5')))
            ->add('sintomas',TextareaType::class, array('attr' => array('class' => 'form-control', 'rows' => '5')))
            ->add('diagnostico', EntityType::class, array('class' => Diagnostico::class,'placeholder' => 'Seleccione un diagnostico','choice_label' => function ($diagnostico) {
                    return $diagnostico->getCodigoCategoria() . ' ' . $diagnostico->getDescripcion();
                },'required' => true, 'attr' => array('class' => 'form-control')))
            ->add('codigoEspecifico',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HistoriaMedica::class,
        ]);
    }
}
