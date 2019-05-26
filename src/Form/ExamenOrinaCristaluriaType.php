<?php

namespace App\Form;

use App\Entity\ExamenOrinaCristaluria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenOrinaCristaluriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uratosAmorfos')
            ->add('acidoUrico')
            ->add('oxalatosCalcicos')
            ->add('fosfatosAmorfos')
            ->add('fosfatosCalcicos')
            ->add('fosfatosAmonicos')
            ->add('riesgoLitogenico')
            ->add('creado_en')
            ->add('actualizado_en')
            ->add('examen_solicitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenOrinaCristaluria::class,
        ]);
    }
}
