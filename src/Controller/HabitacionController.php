<?php

namespace App\Controller;

use App\Entity\Habitacion;
use App\Entity\TipoHabitacion;
use App\Entity\Sala;
use App\Entity\Clinica;
use App\Repository\HabitacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/habitacion")
 */
class HabitacionController extends AbstractController
{
    /**
     * @Route("/{clinica}", name="habitacion_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_HABITACION')")
     */
    public function index(HabitacionRepository $habitacionRepository,Security $AuthUser, Clinica $clinica): Response
    {
        return $this->render('habitacion/index.html.twig', [
            'habitaciones' => $habitacionRepository->findAll(),
            'user' => $AuthUser,
            'clinica' => $clinica,
        ]);
    }

    /**
     * @Route("/new/{clinica}", name="habitacion_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_HABITACION')")
     */
    public function new(Request $request, Clinica $clinica): Response
    {
        $editar=false;
        $habitacion = new Habitacion();
        $form = $this->createFormBuilder($habitacion)
        ->add('sala', EntityType::class, array('class' => Sala::class,'placeholder' => 'Seleccione una sala','choice_label' => 'nombreSala',
                'query_builder' => function (EntityRepository $er) use ($clinica) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.clinica = :val')
                    ->setParameter('val', (int) $clinica->getId());
            },'attr' => array('class' => 'form-control')))
            ->add('tipoHabitacion', EntityType::class, array('class' => TipoHabitacion::class,'placeholder' => 'Seleccione el tipo de habitacion','choice_label' => 'tipoHabitacion','attr' => array('class' => 'form-control')))
            ->add('numeroHabitacion', IntegerType::class, array('attr' => array('class' => 'form-control','min'=>1)))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $salaSeleccionada = $this->getDoctrine()->getRepository(Sala::class)->find($form["sala"]->getData());
            $inicialSala = $salaSeleccionada->getNombreSala()[0];
            $habitacion->setNumeroHabitacion($form['numeroHabitacion']->getData());
            $habitacion->setSala($salaSeleccionada);
            $habitacion->setTipoHabitacion($form["tipoHabitacion"]->getData());
            $entityManager->persist($habitacion);
            $entityManager->flush();

            return $this->redirectToRoute('habitacion_index',['clinica' => $clinica->getId()]);
        }

        return $this->render('habitacion/new.html.twig', [
            'habitacion' => $habitacion,
            'editar'    => $editar,
            'clinica'   => $clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{clinica}", name="habitacion_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_HABITACION')")
     */
    public function show(Habitacion $habitacion, Clinica $clinica): Response
    {
        return $this->render('habitacion/show.html.twig', [
            'habitacion' => $habitacion,
            'clinica'   =>$clinica,
        ]);
    }

    /**
     * @Route("/{id}/{clinica}/edit", name="habitacion_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_HABITACION')")
     */
    public function edit(Request $request, Habitacion $habitacion, Clinica $clinica): Response
    {
        $editar = true;
        $form = $this->createFormBuilder($habitacion)
        ->add('sala', EntityType::class, array('class' => Sala::class,'placeholder' => 'Seleccione una sala','choice_label' => 'nombreSala',
                'query_builder' => function (EntityRepository $er) use ($clinica) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.clinica = :val')
                    ->setParameter('val', (int) $clinica->getId());
            },'attr' => array('class' => 'form-control')))
            ->add('tipoHabitacion', EntityType::class, array('class' => TipoHabitacion::class,'placeholder' => 'Seleccione el tipo de habitacion','choice_label' => 'tipoHabitacion','attr' => array('class' => 'form-control')))
            ->add('numeroHabitacion', IntegerType::class, array('attr' => array('class' => 'form-control')))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('habitacion_index', [
                'clinica' => $clinica->getId(),
            ]);
        }

        return $this->render('habitacion/edit.html.twig', [
            'habitacion' => $habitacion,
            'editar'    => $editar,
            'clinica'   => $clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{clinica}", name="habitacion_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_HABITACION')")
     */
    public function delete(Request $request, Habitacion $habitacion, Clinica $clinica): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habitacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($habitacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('habitacion_index',[
                'clinica' => $clinica->getId(),
            ]);
    }
}
