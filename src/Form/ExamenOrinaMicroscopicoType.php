<?php

namespace App\Form;

use App\Entity\ExamenOrinaMicroscopico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenOrinaMicroscopicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uretral')
            ->add('urotelio')
            ->add('renal')
            ->add('leucocitos')
            ->add('piocitos')
            ->add('eritrocitos')
            ->add('bacteria')
            ->add('parasitos')
            ->add('funguria')
            ->add('filamentoDeMucina')
            ->add('proteinaUromocoide')
            ->add('cilindros')
            ->add('creado_en')
            ->add('actualizado_en')
            ->add('examen_solicitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenOrinaMicroscopico::class,
        ]);
    }
}
