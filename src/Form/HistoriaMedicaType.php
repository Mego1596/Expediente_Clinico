<?php

namespace App\Form;

use App\Entity\HistoriaMedica;
use App\Entity\Diagnostico;
use App\Entity\CodigoInternacional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class HistoriaMedicaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consultaPor',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('signos',TextareaType::class, array('attr' => array('class' => 'form-control', 'rows' => '5')))
            ->add('sintomas',TextareaType::class, array('attr' => array('class' => 'form-control', 'rows' => '5')))
            /*->add('diagnostico', EntityType::class, array('class' => Diagnostico::class,'placeholder' => 'Seleccione un diagnostico','choice_label' => function ($diagnostico) {
                    return $diagnostico->getCodigoCategoria() . ' ' . $diagnostico->getDescripcion();
                },'required' => true, 'attr' => array('class' => 'form-control')))*/
            ->add('id10', EntityType::class, array('class' => CodigoInternacional::class,'placeholder' => 'Seleccione los diagnosticos posibles:',
            'choice_label' => 
                function (CodigoInternacional $cie10) {
                return $cie10->getId10().' '. $cie10->getDec10();
                },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id10', 'ASC');
            },
            'group_by' => 'id10',
            'by_reference' => false,
            'multiple' => true,'expanded' => false,
            'attr' => array('class' => 'form-control','size' => 25)))
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
