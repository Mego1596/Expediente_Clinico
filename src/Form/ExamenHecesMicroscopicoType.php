<?php

namespace App\Form;

use App\Entity\ExamenHecesMicroscopico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenHecesMicroscopicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hematies')
            ->add('leucocito')
            ->add('floraBacteriana')
            ->add('levadura')
            ->add('creado_en')
            ->add('actualizado_en')
            ->add('examen_solicitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHecesMicroscopico::class,
        ]);
    }
}