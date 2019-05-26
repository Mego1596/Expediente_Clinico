<?php

namespace App\Form;

use App\Entity\ExamenHecesMacroscopico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenHecesMacroscopicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aspecto')
            ->add('consistencia')
            ->add('color')
            ->add('olor')
            ->add('presencia_de_sangre')
            ->add('restos_alimenticios')
            ->add('presencia_moco')
            ->add('creado_en')
            ->add('actualizado_en')
            ->add('examen_solicitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHecesMacroscopico::class,
        ]);
    }
}
