<?php

namespace App\Form;

use App\Entity\ExamenHecesQuimico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenHecesQuimicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ph')
            ->add('azucares_reductores')
            ->add('sangre_oculta')
            ->add('creado_en')
            ->add('actualizado_en')
            ->add('examen_solicitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenHecesQuimico::class,
        ]);
    }
}
