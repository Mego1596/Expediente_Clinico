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
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId()){
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY = "SELECT h.*, s.nombre_sala FROM habitacion as h, sala as s, clinica as c WHERE
                h.sala_id = s.id AND s.clinica_id = c.id AND 
                c.id = ".$clinica->getId().";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                return $this->render('habitacion/index.html.twig', [
                    'habitaciones' => $result,
                    'user' => $AuthUser,
                    'clinica' => $clinica,
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('habitacion_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT h.*, s.nombre_sala FROM habitacion as h, sala as s, clinica as c WHERE
        h.sala_id = s.id AND s.clinica_id = c.id AND 
        c.id = ".$clinica->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('habitacion/index.html.twig', [
            'habitaciones' => $result,
            'user' => $AuthUser,
            'clinica' => $clinica,
        ]);
    }

    /**
     * @Route("/new/{clinica}", name="habitacion_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_HABITACION')")
     */
    public function new(Request $request, Clinica $clinica, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId()){
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
                    if($form["sala"]->getData() != ""){
                        $em = $this->getDoctrine()->getManager();
                        $RAW_QUERY = "SELECT h.* FROM habitacion as h, sala as s, clinica as cli 
                        WHERE h.sala_id=s.id AND s.clinica_id = cli.id AND cli.id =".$clinica->getId().
                        " AND numero_habitacion = ".$form["numeroHabitacion"]->getData().
                        " AND sala_id = ".$form["sala"]->getData()->getId().";";
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->execute();
                        $result = $statement->fetchAll();
                        if($result == null){
                            if($form["sala"]->getData() != ""){
                                if($form["tipoHabitacion"]->getData() != ""){
                                    if($form["numeroHabitacion"]->getData() != ""){
                                        //INICIO DE PROCESO DE DATOS
                                        $salaSeleccionada = $this->getDoctrine()->getRepository(Sala::class)->find($form["sala"]->getData());
                                        $habitacion->setNumeroHabitacion($form['numeroHabitacion']->getData());
                                        $habitacion->setSala($salaSeleccionada);
                                        $habitacion->setTipoHabitacion($form["tipoHabitacion"]->getData());
                                        $entityManager->persist($habitacion);
                                        $entityManager->flush();
                                        //FIN DE PROCESO DE DATOS
                                    }else{
                                        $this->addFlash('fail', 'Error, el numero de habitacion no puede estar vacio');
                                        return $this->render('habitacion/new.html.twig', [
                                            'habitacion' => $habitacion,
                                            'editar'    => $editar,
                                            'clinica'   => $clinica,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, por favor elija un tipo de habitacion, no puede estar vacio');
                                    return $this->render('habitacion/new.html.twig', [
                                        'habitacion' => $habitacion,
                                        'editar'    => $editar,
                                        'clinica'   => $clinica,
                                        'form' => $form->createView(),
                                    ]); 
                                }
                            }else{
                                $this->addFlash('fail', 'Error, por favor elija una sala, no puede estar vacia');
                                return $this->render('habitacion/new.html.twig', [
                                    'habitacion' => $habitacion,
                                    'editar'    => $editar,
                                    'clinica'   => $clinica,
                                    'form' => $form->createView(),
                                ]);
                            }
                            $this->addFlash('success','Habitacion añadida con exito');
                            return $this->redirectToRoute('habitacion_index',['clinica' => $clinica->getId()]);

                        }else{
                            $this->addFlash('fail', 'Error, el numero de habitacion en esta sala ya esta registrado, por favor ingrese un numero diferente de habitacion');
                            return $this->render('habitacion/new.html.twig', [
                                'habitacion' => $habitacion,
                                'editar'    => $editar,
                                'clinica'   => $clinica,
                                'form' => $form->createView(),
                            ]);
                        }
                        
                        $this->addFlash('success','Habitacion añadida con exito');
                        return $this->redirectToRoute('habitacion_index',['clinica' => $clinica->getId()]);
                    }else{
                        $this->addFlash('fail', 'Error, por favor elija una sala, no puede estar vacia');
                        return $this->render('habitacion/new.html.twig', [
                            'habitacion' => $habitacion,
                            'editar'    => $editar,
                            'clinica'   => $clinica,
                            'form' => $form->createView(),
                        ]);
                    }
                    
                }

                return $this->render('habitacion/new.html.twig', [
                    'habitacion' => $habitacion,
                    'editar'    => $editar,
                    'clinica'   => $clinica,
                    'form' => $form->createView(),
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('habitacion_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }
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
            if($form["sala"]->getData() != ""){
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY = "SELECT h.* FROM habitacion as h, sala as s, clinica as cli 
                WHERE h.sala_id=s.id AND s.clinica_id = cli.id AND cli.id =".$clinica->getId().
                " AND numero_habitacion = ".$form["numeroHabitacion"]->getData().
                " AND sala_id = ".$form["sala"]->getData()->getId().";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                if($result == null){
                    if($form["sala"]->getData() != ""){
                        if($form["tipoHabitacion"]->getData() != ""){
                            if($form["numeroHabitacion"]->getData() != ""){
                                //INICIO DE PROCESO DE DATOS
                                $salaSeleccionada = $this->getDoctrine()->getRepository(Sala::class)->find($form["sala"]->getData());
                                $habitacion->setNumeroHabitacion($form['numeroHabitacion']->getData());
                                $habitacion->setSala($salaSeleccionada);
                                $habitacion->setTipoHabitacion($form["tipoHabitacion"]->getData());
                                $entityManager->persist($habitacion);
                                $entityManager->flush();
                                //FIN DE PROCESO DE DATOS
                            }else{
                                $this->addFlash('fail', 'Error, el numero de habitacion no puede estar vacio');
                                return $this->render('habitacion/new.html.twig', [
                                    'habitacion' => $habitacion,
                                    'editar'    => $editar,
                                    'clinica'   => $clinica,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, por favor elija un tipo de habitacion, no puede estar vacio');
                            return $this->render('habitacion/new.html.twig', [
                                'habitacion' => $habitacion,
                                'editar'    => $editar,
                                'clinica'   => $clinica,
                                'form' => $form->createView(),
                            ]); 
                        }
                    }else{
                        $this->addFlash('fail', 'Error, por favor elija una sala, no puede estar vacia');
                        return $this->render('habitacion/new.html.twig', [
                            'habitacion' => $habitacion,
                            'editar'    => $editar,
                            'clinica'   => $clinica,
                            'form' => $form->createView(),
                        ]);
                    }
                    $this->addFlash('success','Habitacion añadida con exito');
                    return $this->redirectToRoute('habitacion_index',['clinica' => $clinica->getId()]);

                }else{
                    $this->addFlash('fail', 'Error, el numero de habitacion en esta sala ya esta registrado, por favor ingrese un numero diferente de habitacion');
                    return $this->render('habitacion/new.html.twig', [
                        'habitacion' => $habitacion,
                        'editar'    => $editar,
                        'clinica'   => $clinica,
                        'form' => $form->createView(),
                    ]);
                }
                
                $this->addFlash('success','Habitacion añadida con exito');
                return $this->redirectToRoute('habitacion_index',['clinica' => $clinica->getId()]);
            }else{
                $this->addFlash('fail', 'Error, por favor elija una sala, no puede estar vacia');
                return $this->render('habitacion/new.html.twig', [
                    'habitacion' => $habitacion,
                    'editar'    => $editar,
                    'clinica'   => $clinica,
                    'form' => $form->createView(),
                ]);
            }
            
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
    public function show(Habitacion $habitacion, Clinica $clinica, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId() && $AuthUser->getUser()->getClinica()->getId() == $habitacion->getSala()->getClinica()->getId() ){
                return $this->render('habitacion/show.html.twig', [
                    'habitacion' => $habitacion,
                    'clinica'   =>$clinica,
                ]);       
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('habitacion_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }
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
    public function edit(Request $request, Habitacion $habitacion, Clinica $clinica,Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId() && $AuthUser->getUser()->getClinica()->getId() == $habitacion->getSala()->getClinica()->getId() ){
                    $editar = true;
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT s.* FROM sala as s, clinica as cli 
                    WHERE s.clinica_id = cli.id AND cli.id =".$clinica->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $salas = $statement->fetchAll();

                    $form = $this->createFormBuilder($habitacion)
                    ->add('tipoHabitacion', EntityType::class, array('class' => TipoHabitacion::class,'placeholder' => 'Seleccione el tipo de habitacion','choice_label' => 'tipoHabitacion','attr' => array('class' => 'form-control')))
                    ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
                    ->getForm();
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $em = $this->getDoctrine()->getManager();
                        $RAW_QUERY = "SELECT h.* FROM habitacion as h, sala as s, clinica as cli 
                        WHERE h.sala_id=s.id AND s.clinica_id = cli.id AND cli.id =".$clinica->getId().
                        " AND sala_id = ".$request->request->get('sala').";";
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->execute();
                        $result = $statement->fetchAll();
                        if($result == null ){
                            if($request->request->get('sala') != 0){
                                $habitacion->setSala($this->getDoctrine()->getRepository(Sala::class)->find($request->request->get('sala')) );
                                $this->getDoctrine()->getManager()->flush();
                            }
                        }else{
                            if($habitacion->getId() != $result[0]['id']){
                                $this->addFlash('fail','Error, ya existe una habitacion en la sala seleccionada');
                                return $this->render('habitacion/edit.html.twig', [
                                    'habitacion' => $habitacion,
                                    'editar'    => $editar,
                                    'clinica'   => $clinica,
                                    'salas'     => $salas,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }

                        
                        $this->addFlash('success','Habitacion modificada con exito');
                        return $this->redirectToRoute('habitacion_index', [
                            'clinica' => $clinica->getId(),
                        ]);
                    }

                    return $this->render('habitacion/edit.html.twig', [
                        'habitacion' => $habitacion,
                        'editar'    => $editar,
                        'clinica'   => $clinica,
                        'salas'     => $salas,
                        'form' => $form->createView(),
                    ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('habitacion_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }
        $editar = true;
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT s.* FROM sala as s, clinica as cli 
        WHERE s.clinica_id = cli.id AND cli.id =".$clinica->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $salas = $statement->fetchAll();

        $form = $this->createFormBuilder($habitacion)
        ->add('tipoHabitacion', EntityType::class, array('class' => TipoHabitacion::class,'placeholder' => 'Seleccione el tipo de habitacion','choice_label' => 'tipoHabitacion','attr' => array('class' => 'form-control')))
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT h.* FROM habitacion as h, sala as s, clinica as cli 
            WHERE h.sala_id=s.id AND s.clinica_id = cli.id AND cli.id =".$clinica->getId().
            " AND sala_id = ".$request->request->get('sala').";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if($result == null ){
                if($request->request->get('sala') != 0){
                    $habitacion->setSala($this->getDoctrine()->getRepository(Sala::class)->find($request->request->get('sala')) );
                    $this->getDoctrine()->getManager()->flush();
                }
            }else{
                if($habitacion->getId() != $result[0]['id']){
                    $this->addFlash('fail','Error, ya existe una habitacion en la sala seleccionada');
                    return $this->render('habitacion/edit.html.twig', [
                        'habitacion' => $habitacion,
                        'editar'    => $editar,
                        'clinica'   => $clinica,
                        'salas'     => $salas,
                        'form' => $form->createView(),
                    ]);
                }
            }

            
            $this->addFlash('success','Habitacion modificada con exito');
            return $this->redirectToRoute('habitacion_index', [
                'clinica' => $clinica->getId(),
            ]);
        }

        return $this->render('habitacion/edit.html.twig', [
            'habitacion' => $habitacion,
            'editar'    => $editar,
            'clinica'   => $clinica,
            'salas'     => $salas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{clinica}", name="habitacion_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_HABITACION')")
     */
    public function delete(Request $request, Habitacion $habitacion, Clinica $clinica, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId() && $AuthUser->getUser()->getClinica()->getId() == $habitacion->getSala()->getClinica()->getId() ){
                if ($this->isCsrfTokenValid('delete'.$habitacion->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($habitacion);
                    $entityManager->flush();
                }
                $this->addFlash('success','Habitacion eliminada con exito');
                return $this->redirectToRoute('habitacion_index',[
                    'clinica' => $clinica->getId(),
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('habitacion_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }
        if ($this->isCsrfTokenValid('delete'.$habitacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($habitacion);
            $entityManager->flush();
        }
        $this->addFlash('success','Habitacion eliminada con exito');
        return $this->redirectToRoute('habitacion_index',[
            'clinica' => $clinica->getId(),
        ]);
    }
}
