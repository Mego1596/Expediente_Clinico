<?php

namespace App\Controller;

use App\Entity\Cita;
use App\Entity\User;
use App\Entity\Especialidad;
use App\Entity\Expediente;
use App\Form\CitaType;
use App\Repository\CitaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/cita")
 */
class CitaController extends AbstractController
{
    /**
     * @Route("/{expediente}", name="cita_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_CITA')")
     */
    public function index(CitaRepository $citaRepository,Expediente $expediente,Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_PACIENTE'){
                        $em = $this->getDoctrine()->getManager();
                        $RAW_QUERY = "SELECT id,consulta_por as consultaPor,fecha_reservacion as fechaReservacion,fecha_fin as fechaFin FROM cita WHERE expediente_id = ".$expediente->getId().";";

                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->execute();
                        $result = $statement->fetchAll();
                        return $this->render('cita/index.html.twig', [
                            'citas' => $result,
                            'expediente' => $expediente,
                            'user'       => $AuthUser,
                        ]);
                    }else{
                        $this->addFlash('fail','No esta autorizado para ver esta pagina');
                        return $this->redirectToRoute('home');
                    }
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }

        if($expediente->getHabilitado()){
            if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_PACIENTE'){
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY = "SELECT id,consulta_por as consultaPor,fecha_reservacion as fechaReservacion,fecha_fin as fechaFin FROM cita WHERE expediente_id = ".$expediente->getId().";";

                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                return $this->render('cita/index.html.twig', [
                    'citas' => $result,
                    'expediente' => $expediente,
                    'user'       => $AuthUser,
                ]);
            }else{
                $this->addFlash('fail','No esta autorizado para ver esta pagina');
                return $this->redirectToRoute('home');
            }
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }


    /**
     * @Route("/new/{expediente}", name="cita_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_CITA')")
     */
    public function new(Request $request,Expediente $expediente,Security $AuthUser): Response
    {   
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    $editar = false;
                    $citum = new Cita();
                    date_default_timezone_set("America/El_Salvador");
                    if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                        $especialidades = $this->getDoctrine()->getRepository(Especialidad::class)->findAll();
                    }else{
                        $especialidades = null;
                    }
                    $form = $this->createForm(CitaType::class, $citum);
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        if($form["fechaReservacion"]->getData() != ""){
                            if($request->request->get('user') != ""){
                                if($form["consultaPor"]->getData() != ""){
                                    if($request->request->get('time2') != ""){
                                        //INICIO FUNCIONALIDAD INGRESAR UNA CITA
                                        $horaSeleccionada =$request->request->get('time2')[3].$request->request->get('time2')[4];
                                        $fecha=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                                        $fechaFin=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                                        $hoy = date_create();
                                        $em = $this->getDoctrine()->getManager();
                                        $RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND usuario_id=".$request->request->get('user').";";
                                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                                        $statement->execute();
                                        $result = $statement->fetchAll();
                                        if($fecha < $hoy){
                                            $this->addFlash('fail','Error, la fecha y hora debe ser mayor o igual que la fecha y hora actual');
                                            return $this->render('cita/new.html.twig', [
                                                'citum' => $citum,
                                                'editar' => $editar,
                                                'expediente' => $expediente,
                                                'especialidades' => $especialidades,
                                                'form' => $form->createView(),
                                            ]);

                                        }elseif ($horaSeleccionada != '00' && $horaSeleccionada != '30') {
                                            $this->addFlash('fail','Error, la hora ingresada no es valida, ingrese una hora puntual u hora y media');
                                            return $this->render('cita/new.html.twig', [
                                                'citum' => $citum,
                                                'editar' => $editar,
                                                'expediente' => $expediente,
                                                'especialidades' => $especialidades,
                                                'form' => $form->createView(),
                                            ]);

                                        }elseif ($result != null) {
                                            $this->addFlash('fail','Error, el doctor que ha seleccionado no tiene disponible ese horario');
                                            return $this->render('cita/new.html.twig', [
                                                'citum' => $citum,
                                                'editar' => $editar,
                                                'expediente' => $expediente,
                                                'especialidades' => $especialidades,
                                                'form' => $form->createView(),
                                            ]);
                                        }else{
                                            $RAW_QUERY=$RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND expediente_id=".$expediente->getId().";";
                                            $statement = $em->getConnection()->prepare($RAW_QUERY);
                                            $statement->execute();
                                            $result2 = $statement->fetchAll();
                                            if($result2 != null){
                                                $this->addFlash('fail','Usted ya tiene una cita agendada a esa hora. Porfavor elija una hora diferente.');
                                                return $this->render('cita/new.html.twig', [
                                                    'citum' => $citum,
                                                    'editar' => $editar,
                                                    'expediente' => $expediente,
                                                    'especialidades' => $especialidades,
                                                    'form' => $form->createView(),
                                                ]);
                                            }else{
                                                $entityManager = $this->getDoctrine()->getManager();
                                                $citum->setExpediente($this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()));
                                                $citum->setFechaReservacion($fecha);
                                                $citum->setFechaFin($fechaFin->modify('+30 minutes'));
                                                $citum->setConsultaPor($form["consultaPor"]->getData());
                                                $citum->setUsuario($this->getDoctrine()->getRepository(User::class)->find($request->request->get('user')));
                                                $entityManager->persist($citum);
                                                $entityManager->flush();
                                                if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                                                    $this->addFlash('success','Cita añadida con exito');
                                                    return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
                                                }else{
                                                    $this->addFlash('success','Cita añadida con exito');
                                                    return $this->redirectToRoute('cita_calendar',['expediente' => $expediente->getId()]);
                                                }
                                            }
                                        }
                                        //FIN FUNCIONALIDAD INGRESAR UNA CITA
                                    }else{
                                        $this->addFlash('fail', 'Error, el campo de la hora no puede ir vacio');
                                        return $this->render('cita/new.html.twig', [
                                            'citum' => $citum,
                                            'editar' => $editar,
                                            'expediente' => $expediente,
                                            'especialidades' => $especialidades,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, el campo consulta por no puede ir vacio');
                                    return $this->render('cita/new.html.twig', [
                                        'citum' => $citum,
                                        'editar' => $editar,
                                        'expediente' => $expediente,
                                        'especialidades' => $especialidades,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, el campo de doctores disponibles no puede ir vacio');
                                return $this->render('cita/new.html.twig', [
                                    'citum' => $citum,
                                    'editar' => $editar,
                                    'expediente' => $expediente,
                                    'especialidades' => $especialidades,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, el campo de fecha de reservacion no puede ir vacio');
                            return $this->render('cita/new.html.twig', [
                                'citum' => $citum,
                                'editar' => $editar,
                                'expediente' => $expediente,
                                'especialidades' => $especialidades,
                                'form' => $form->createView(),
                            ]);
                        }
                    }
                    return $this->render('cita/new.html.twig', [
                        'citum' => $citum,
                        'editar' => $editar,
                        'expediente' => $expediente,
                        'especialidades' => $especialidades,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }

        if($expediente->getHabilitado()){
            $editar = false;
            $citum = new Cita();
            date_default_timezone_set("America/El_Salvador");
            if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                $especialidades = $this->getDoctrine()->getRepository(Especialidad::class)->findAll();
            }else{
                $especialidades = null;
            }
            $form = $this->createForm(CitaType::class, $citum);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if($form["fechaReservacion"]->getData() != ""){
                    if($request->request->get('user') != ""){
                        if($form["consultaPor"]->getData() != ""){
                            if($request->request->get('time2') != ""){
                                //INICIO FUNCIONALIDAD INGRESAR UNA CITA
                                $horaSeleccionada =$request->request->get('time2')[3].$request->request->get('time2')[4];
                                $fecha=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                                $fechaFin=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                                $hoy = date_create();
                                $em = $this->getDoctrine()->getManager();
                                $RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND usuario_id=".$request->request->get('user').";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                if($fecha < $hoy){
                                    $this->addFlash('fail','Error, la fecha y hora debe ser mayor o igual que la fecha y hora actual');
                                    return $this->render('cita/new.html.twig', [
                                        'citum' => $citum,
                                        'editar' => $editar,
                                        'expediente' => $expediente,
                                        'especialidades' => $especialidades,
                                        'form' => $form->createView(),
                                    ]);

                                }elseif ($horaSeleccionada != '00' && $horaSeleccionada != '30') {
                                    $this->addFlash('fail','Error, la hora ingresada no es valida, ingrese una hora puntual u hora y media');
                                    return $this->render('cita/new.html.twig', [
                                        'citum' => $citum,
                                        'editar' => $editar,
                                        'expediente' => $expediente,
                                        'especialidades' => $especialidades,
                                        'form' => $form->createView(),
                                    ]);

                                }elseif ($result != null) {
                                    $this->addFlash('fail','Error, el doctor que ha seleccionado no tiene disponible ese horario');
                                    return $this->render('cita/new.html.twig', [
                                        'citum' => $citum,
                                        'editar' => $editar,
                                        'expediente' => $expediente,
                                        'especialidades' => $especialidades,
                                        'form' => $form->createView(),
                                    ]);
                                }else{
                                    $RAW_QUERY=$RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND expediente_id=".$expediente->getId().";";
                                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                                    $statement->execute();
                                    $result2 = $statement->fetchAll();
                                    if($result2 != null){
                                        $this->addFlash('fail','Usted ya tiene una cita agendada a esa hora. Porfavor elija una hora diferente.');
                                        return $this->render('cita/new.html.twig', [
                                            'citum' => $citum,
                                            'editar' => $editar,
                                            'expediente' => $expediente,
                                            'especialidades' => $especialidades,
                                            'form' => $form->createView(),
                                        ]);
                                    }else{
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $citum->setExpediente($this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()));
                                        $citum->setFechaReservacion($fecha);
                                        $citum->setFechaFin($fechaFin->modify('+30 minutes'));
                                        $citum->setConsultaPor($form["consultaPor"]->getData());
                                        $citum->setUsuario($this->getDoctrine()->getRepository(User::class)->find($request->request->get('user')));
                                        $entityManager->persist($citum);
                                        $entityManager->flush();
                                        if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                                            $this->addFlash('success','Cita añadida con exito');
                                            return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
                                        }else{
                                            $this->addFlash('success','Cita añadida con exito');
                                            return $this->redirectToRoute('cita_calendar',['expediente' => $expediente->getId()]);
                                        }
                                    }
                                }
                                //FIN FUNCIONALIDAD INGRESAR UNA CITA
                            }else{
                                $this->addFlash('fail', 'Error, el campo de la hora no puede ir vacio');
                                return $this->render('cita/new.html.twig', [
                                    'citum' => $citum,
                                    'editar' => $editar,
                                    'expediente' => $expediente,
                                    'especialidades' => $especialidades,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, el campo consulta por no puede ir vacio');
                            return $this->render('cita/new.html.twig', [
                                'citum' => $citum,
                                'editar' => $editar,
                                'expediente' => $expediente,
                                'especialidades' => $especialidades,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, el campo de doctores disponibles no puede ir vacio');
                        return $this->render('cita/new.html.twig', [
                            'citum' => $citum,
                            'editar' => $editar,
                            'expediente' => $expediente,
                            'especialidades' => $especialidades,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error, el campo de fecha de reservacion no puede ir vacio');
                    return $this->render('cita/new.html.twig', [
                        'citum' => $citum,
                        'editar' => $editar,
                        'expediente' => $expediente,
                        'especialidades' => $especialidades,
                        'form' => $form->createView(),
                    ]);
                }
            }
            return $this->render('cita/new.html.twig', [
                'citum' => $citum,
                'editar' => $editar,
                'expediente' => $expediente,
                'especialidades' => $especialidades,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/calendar/{expediente}", name="cita_calendar", methods={"GET"})
     * @Security2("is_authenticated()")
     */
    public function calendar(Expediente $expediente): Response
    {   
        if($expediente->getHabilitado()){
            return $this->render('cita/calendar.html.twig',[
                'expediente' =>$expediente,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{expediente}/{id}/", name="cita_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_CITA')")
     */
    public function show(Cita $citum,Expediente $expediente,Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId() && $citum->getExpediente()->getId() == $expediente->getId() ){
                if($expediente->getHabilitado()){
                    return $this->render('cita/show.html.twig', [
                        'citum'      => $citum,
                        'user'       => $AuthUser,
                        'expediente' => $expediente,
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        } 

        if($expediente->getHabilitado()){
            return $this->render('cita/show.html.twig', [
                'citum'      => $citum,
                'user'       => $AuthUser,
                'expediente' => $expediente,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{expediente}/{id}/edit", name="cita_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_CITA')")
     */
    public function edit(Request $request, Cita $citum,Expediente $expediente,Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId() && $citum->getExpediente()->getId() == $expediente->getId() ){
                if($expediente->getHabilitado()){
                    if($expediente->getHabilitado()){
                        $editar = true;
                        if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                            $especialidades = $this->getDoctrine()->getRepository(Especialidad::class)->findAll();
                        }else{
                            $especialidades = null;
                        }
                        $form = $this->createForm(CitaType::class, $citum);
                        $form->handleRequest($request);

                        if ($form->isSubmitted() && $form->isValid()) {
                            if($request->request->get('user') != ""){
                                date_default_timezone_set("America/El_Salvador");
                                $horaSeleccionada =$request->request->get('time2')[3].$request->request->get('time2')[4];
                                $fecha=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                                $fechaFin=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                                $hoy = date_create();
                                $em = $this->getDoctrine()->getManager();
                                $RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND usuario_id=".$request->request->get('user').";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                if($fecha < $hoy){
                                    $this->addFlash('fail','Error, la fecha y hora debe ser mayor o igual que la fecha y hora actual');
                                    return $this->render('cita/edit.html.twig', [
                                        'citum' => $citum,
                                        'editar' => $editar,
                                        'expediente' => $expediente,
                                        'user'  => $citum->getUsuario(),
                                        'loggedUser' => $AuthUser,
                                        'especialidades' => $especialidades,
                                        'form' => $form->createView(),
                                    ]);

                                }elseif ($horaSeleccionada != '00' && $horaSeleccionada != '30') {
                                    $this->addFlash('fail','Error, la hora ingresada no es valida, ingrese una hora puntual u hora y media');
                                    return $this->render('cita/edit.html.twig', [
                                        'citum' => $citum,
                                        'editar' => $editar,
                                        'expediente' => $expediente,
                                        'user'  => $citum->getUsuario(),
                                        'loggedUser' => $AuthUser,
                                        'especialidades' => $especialidades,
                                        'form' => $form->createView(),
                                    ]);

                                }elseif ($result != null) {
                                    $this->addFlash('fail','Error, el doctor que ha seleccionado no tiene disponible ese horario');
                                    return $this->render('cita/edit.html.twig', [
                                        'citum' => $citum,
                                        'editar' => $editar,
                                        'expediente' => $expediente,
                                        'user'  => $citum->getUsuario(),
                                        'loggedUser' => $AuthUser,
                                        'especialidades' => $especialidades,
                                        'form' => $form->createView(),
                                    ]);
                                }else{
                                    $RAW_QUERY=$RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND expediente_id=".$expediente->getId().";";
                                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                                    $statement->execute();
                                    $result2 = $statement->fetchAll();
                                    if($result2 != null){
                                        $this->addFlash('fail','Usted ya tiene una cita agendada a esa hora. Porfavor elija una hora diferente.');
                                        return $this->render('cita/edit.html.twig', [
                                            'citum' => $citum,
                                            'editar' => $editar,
                                            'expediente' => $expediente,
                                            'user'  => $citum->getUsuario(),
                                            'loggedUser' => $AuthUser,
                                            'especialidades' => $especialidades,
                                            'form' => $form->createView(),
                                        ]);
                                    }else{
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $citum->setExpediente($this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()));
                                        $citum->setFechaReservacion($fecha);
                                        $citum->setFechaFin($fechaFin->modify('+30 minutes'));
                                        $citum->setConsultaPor($form["consultaPor"]->getData());
                                        $citum->setUsuario($this->getDoctrine()->getRepository(User::class)->find($request->request->get('user')));
                                        $entityManager->persist($citum);
                                        $entityManager->flush();
                                        if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                                            $this->addFlash('success','Cita Modificada con exito');
                                            return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
                                        }else{
                                            $this->addFlash('success','Cita Modificada con exito');
                                            return $this->redirectToRoute('cita_calendar',['expediente' => $expediente->getId()]);
                                        }
                                    }
                                }
                            }else{
                                $entityManager = $this->getDoctrine()->getManager();
                                $fechaReservacionAuxiliar = $citum->getFechaFin();
                                $citum->setFechaReservacion($fechaReservacionAuxiliar->modify('-30 minutes')); 
                                $entityManager->persist($citum);
                                $entityManager->flush();
                                if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                                    $this->addFlash('success','Cita Modificada con exito');
                                    return $this->redirectToRoute('cita_index', [
                                        'id' => $citum->getId(),
                                        'expediente' => $expediente->getId(),
                                    ]);
                                }else{
                                    $this->addFlash('success','Cita Modificada con exito');
                                    return $this->redirectToRoute('cita_calendar', [
                                        'expediente' => $expediente->getId(),
                                    ]);
                                }
                            }
                        }

                        return $this->render('cita/edit.html.twig', [
                            'citum' => $citum,
                            'editar' => $editar,
                            'user'  => $citum->getUsuario(),
                            'loggedUser' => $AuthUser,
                            'especialidades' => $especialidades,
                            'expediente' => $expediente,
                            'form' => $form->createView(),
                        ]);
                    }else{
                        $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                        return $this->redirectToRoute('expediente_index');
                    }
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        } 

        if($expediente->getHabilitado()){
            $editar = true;
            if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                $especialidades = $this->getDoctrine()->getRepository(Especialidad::class)->findAll();
            }else{
                $especialidades = null;
            }
            $form = $this->createForm(CitaType::class, $citum);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if($request->request->get('user') != ""){
                    date_default_timezone_set("America/El_Salvador");
                    $horaSeleccionada =$request->request->get('time2')[3].$request->request->get('time2')[4];
                    $fecha=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                    $fechaFin=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                    $hoy = date_create();
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND usuario_id=".$request->request->get('user').";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if($fecha < $hoy){
                        $this->addFlash('fail','Error, la fecha y hora debe ser mayor o igual que la fecha y hora actual');
                        return $this->render('cita/edit.html.twig', [
                            'citum' => $citum,
                            'editar' => $editar,
                            'expediente' => $expediente,
                            'user'  => $citum->getUsuario(),
                            'loggedUser' => $AuthUser,
                            'especialidades' => $especialidades,
                            'form' => $form->createView(),
                        ]);

                    }elseif ($horaSeleccionada != '00' && $horaSeleccionada != '30') {
                        $this->addFlash('fail','Error, la hora ingresada no es valida, ingrese una hora puntual u hora y media');
                        return $this->render('cita/edit.html.twig', [
                            'citum' => $citum,
                            'editar' => $editar,
                            'expediente' => $expediente,
                            'user'  => $citum->getUsuario(),
                            'loggedUser' => $AuthUser,
                            'especialidades' => $especialidades,
                            'form' => $form->createView(),
                        ]);

                    }elseif ($result != null) {
                        $this->addFlash('fail','Error, el doctor que ha seleccionado no tiene disponible ese horario');
                        return $this->render('cita/edit.html.twig', [
                            'citum' => $citum,
                            'editar' => $editar,
                            'expediente' => $expediente,
                            'user'  => $citum->getUsuario(),
                            'loggedUser' => $AuthUser,
                            'especialidades' => $especialidades,
                            'form' => $form->createView(),
                        ]);
                    }else{
                        $RAW_QUERY=$RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND expediente_id=".$expediente->getId().";";
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->execute();
                        $result2 = $statement->fetchAll();
                        if($result2 != null){
                            $this->addFlash('fail','Usted ya tiene una cita agendada a esa hora. Porfavor elija una hora diferente.');
                            return $this->render('cita/edit.html.twig', [
                                'citum' => $citum,
                                'editar' => $editar,
                                'expediente' => $expediente,
                                'user'  => $citum->getUsuario(),
                                'loggedUser' => $AuthUser,
                                'especialidades' => $especialidades,
                                'form' => $form->createView(),
                            ]);
                        }else{
                            $entityManager = $this->getDoctrine()->getManager();
                            $citum->setExpediente($this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()));
                            $citum->setFechaReservacion($fecha);
                            $citum->setFechaFin($fechaFin->modify('+30 minutes'));
                            $citum->setConsultaPor($form["consultaPor"]->getData());
                            $citum->setUsuario($this->getDoctrine()->getRepository(User::class)->find($request->request->get('user')));
                            $entityManager->persist($citum);
                            $entityManager->flush();
                            if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                                $this->addFlash('success','Cita Modificada con exito');
                                return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
                            }else{
                                $this->addFlash('success','Cita Modificada con exito');
                                return $this->redirectToRoute('cita_calendar',['expediente' => $expediente->getId()]);
                            }
                        }
                    }
                }else{
                    $entityManager = $this->getDoctrine()->getManager();
                    $fechaReservacionAuxiliar = $citum->getFechaFin();
                    $citum->setFechaReservacion($fechaReservacionAuxiliar->modify('-30 minutes')); 
                    $entityManager->persist($citum);
                    $entityManager->flush();
                    if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                        $this->addFlash('success','Cita Modificada con exito');
                        return $this->redirectToRoute('cita_index', [
                            'id' => $citum->getId(),
                            'expediente' => $expediente->getId(),
                        ]);
                    }else{
                        $this->addFlash('success','Cita Modificada con exito');
                        return $this->redirectToRoute('cita_calendar', [
                            'expediente' => $expediente->getId(),
                        ]);
                    }
                }
            }

            return $this->render('cita/edit.html.twig', [
                'citum' => $citum,
                'editar' => $editar,
                'user'  => $citum->getUsuario(),
                'loggedUser' => $AuthUser,
                'especialidades' => $especialidades,
                'expediente' => $expediente,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{expediente}/{id}", name="cita_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_CITA')")
     */
    public function delete(Request $request, Cita $citum,Expediente $expediente,Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId() && $citum->getExpediente()->getId() == $expediente->getId() ){
                if($expediente->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$citum->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($citum);
                        $entityManager->flush();
                    }
                    if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
                        $this->addFlash('success','Cita eliminada con exito');
                        return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
                    }else{
                        $this->addFlash('success','Cita eliminada con exito');
                        return $this->redirectToRoute('cita_calendar',['expediente' => $expediente->getId()]);
                    }
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        } 

        if ($this->isCsrfTokenValid('delete'.$citum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($citum);
            $entityManager->flush();
        }
        if($AuthUser->getUser()->getRol()->getNombreRol()!='ROLE_PACIENTE'){
            $this->addFlash('success','Cita eliminada con exito');
            return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
        }else{
            $this->addFlash('success','Cita eliminada con exito');
            return $this->redirectToRoute('cita_calendar',['expediente' => $expediente->getId()]);
        }
    }
}
