<?php

namespace App\Form;

use App\Entity\ExamenQuimicaSanguinea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenQuimicaSanguineaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parametro')
            ->add('resultado')
            ->add('comentario')
            ->add('unidades')
            ->add('rango')
            ->add('creado_en')
            ->add('actualizado_en')
            ->add('examen_solicitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenQuimicaSanguinea::class,
        ]);
    }
}
