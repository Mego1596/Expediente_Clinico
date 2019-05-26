<?php

namespace App\Form;

use App\Entity\ExamenSolicitado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ExamenSolicitadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoExamen', ChoiceType::class, array('choices'  => array('Seleccione un examen' => null,'Orina' => 1, 'Heces' => 2,'Quimica Sanguinea' => 3,'Hematologico' => 4 ),'attr' => array('class' => 'form-control')))
            ->add('categoria', ChoiceType::class, array('choices'  => array('Seleccione una categoria' => null,'Quimico' => 1, 'Microscopico' => 2,'Macroscopico' => 3, 'Cristaluria' => 4),'attr' => array('class' => 'form-control', 'required' => false)))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')));;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenSolicitado::class,
        ]);
    }
}
