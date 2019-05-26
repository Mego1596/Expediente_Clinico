<?php

namespace App\Form;

use App\Entity\ExamenOrinaQuimico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenOrinaQuimicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('densidad')
            ->add('ph')
            ->add('glucosa')
            ->add('proteinas')
            ->add('hemoglobina')
            ->add('cuerpoCetonico')
            ->add('pigmentoBiliar')
            ->add('urobilinogeno')
            ->add('nitritos')
            ->add('creado_en')
            ->add('actualizado_en')
            ->add('examen_solicitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenOrinaQuimico::class,
        ]);
    }
}
