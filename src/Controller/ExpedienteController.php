<?php

namespace App\Controller;

use App\Entity\Expediente;
use App\Entity\Clinica;
use App\Entity\Genero;
use App\Entity\Rol;
use App\Entity\HistorialIngreso;
use App\Entity\Persona;
use App\Entity\User;
use App\Repository\ExpedienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/expediente")
 */
class ExpedienteController extends AbstractController
{
    /**
     * @Route("/", name="expediente_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_EXPEDIENTE')")
     */
    public function index(ExpedienteRepository $expedienteRepository,Security $AuthUser): Response
    {
        // Obteniendo lista de expedientes
        $ID_CLINICA_I = -1;
        $sql= 'CALL obtener_lista_expedientes(:ID_CLINICA_I)';
        if ($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA')
        {
            $ID_CLINICA_I = $AuthUser->getUser()->getClinica()->getId();
        }
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'ID_CLINICA_I' => $ID_CLINICA_I 
        ));
        $result= $stmt->fetchAll();
        $stmt->closeCursor();

        return $this->render('expediente/index.html.twig', [
            'pacientes' => $result,
            'user'      => $AuthUser,
        ]);
    }

    /**
     * @Route("/new", name="expediente_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_EXPEDIENTE')")
     */
    public function new(Request $request, Security $AuthUser): Response
    {
        $editar = false;
        //PARA CUALQUIER OPERACION QUE SE REALICE CON DATE, AGREGAR ESTE SETEO DE TIMEZONE
        date_default_timezone_set("America/El_Salvador");
        $date = date_add(date_create(),date_interval_create_from_date_string("-1 years"));
        $expediente = new Expediente();
        $persona = new Persona();
        $clinicaPerteneciente = $AuthUser->getUser()->getClinica();

        if(is_null($AuthUser->getUser()->getClinica())){
            $clinicas = $this->getDoctrine()->getRepository(Clinica::class)->findAll();
        }else{
            $clinicas=null;
        }
        $form = $this->createFormBuilder($expediente)
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
            ->add('direccion',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('fechaNacimiento', DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['class' => 'form-control', 'max' => $date->format('Y-m-d')]])
            ->add('telefono',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('apellidoCasada',TextType::class, array( 'required' => false,'attr' => array('class' => 'form-control')))
            ->add('estadoCivil',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('genero', EntityType::class, array('class' => Genero::class, 'placeholder' => 'Seleccione el genero', 'choice_label' => 'descripcion', 'attr' => array('class' => 'form-control') ))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($request->request->get('primerNombre') != ""){
                if($request->request->get('primerApellido') != ""){
                    if($form["email"]->getData() != ""){
                        if($form["direccion"]->getData() != ""){
                            if($form["telefono"]->getData() != ""){
                                if($form["fechaNacimiento"]->getData() != ""){
                                    if($form["genero"]->getData() != ""){
                                        if ($date <= $form["fechaNacimiento"]->getData() ) {
                                            $this->addFlash('fail', 'La fecha de nacimiento debe ser al menos un año menor a la fecha actual');
                                            return $this->render('expediente/new.html.twig', [
                                                    'expediente' => $expediente,
                                                    'clinicas'   => $clinicas,
                                                    'pertenece'  => $clinicaPerteneciente,
                                                    'editar'     => $editar,
                                                    'form'       => $form->createView(),
                                                ]);
                                        }


                                        //INICIO DE PROCESO DE DATOS
                                        $valido = true;
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $usuario = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $form["email"]->getData()]);
                                        if (count($usuario) > 0)
                                        {
                                            $valido = false;
                                            $this->addFlash('fail', 'Usuario con este Email ya existe');
                                            return $this->render('expediente/new.html.twig', [
                                                    'expediente' => $expediente,
                                                    'clinicas'   => $clinicas,
                                                    'pertenece'  => $clinicaPerteneciente,
                                                    'editar'     => $editar,
                                                    'form'       => $form->createView(),
                                                ]);
                                        }

                                        $user = new User();
	                                    $persona->setPrimerNombre($request->request->get('primerNombre'));
                                        $persona->setSegundoNombre($request->request->get('segundoNombre'));
                                        $persona->setPrimerApellido($request->request->get('primerApellido'));
                                        $persona->setSegundoApellido($request->request->get('segundoApellido'));
                                        $user->setEmail($form["email"]->getData());
                                        $user->setRol($this->getDoctrine()->getRepository(Rol::class)->findOneByNombre('ROLE_PACIENTE'));

                                        if(is_null($AuthUser->getUser()->getClinica()) && $valido){
                                            if($request->request->get('clinica') != ""){
                                                $user->setClinica($this->getDoctrine()->getRepository(Clinica::class)->find($request->request->get('clinica')));
                                            } else{
                                                $valido = false;
                                        		$this->addFlash('fail', 'Error, debe elegir la clínica a la cual desea asignar este paciente.');
                                        		return $this->render('expediente/new.html.twig', [
                                                    'expediente' => $expediente,
                                                    'clinicas'   => $clinicas,
                                                    'pertenece'  => $clinicaPerteneciente,
                                                    'editar'     => $editar,
                                                    'form'       => $form->createView(),
                                                ]);
                                            }
                                        } elseif ($valido){
                                            $user->setClinica($this->getDoctrine()->getRepository(Clinica::class)->find($AuthUser->getUser()->getClinica()->getId()));
                                        }
                                        
                                        if(preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $request->request->get('password'))){
                                            $user->setPassword(password_hash($request->request->get('password'),PASSWORD_DEFAULT,[15]));
                                        }else{
                                            $valido = false;
                                            $this->addFlash('notice','La contraseña debe tener al entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula, al menos una mayúscula y al menos un caracter no alfanumérico.');
                                            return $this->render('expediente/new.html.twig', [
                                                'expediente' => $expediente,
                                                'clinicas'   => $clinicas,
                                                'pertenece'  => $clinicaPerteneciente,
                                                'editar'     => $editar,
                                                'form'       => $form->createView(),
                                            ]);
                                        }

                                        $user->setIsActive(true);
                                        $entityManager->persist($persona);
                                        $entityManager->flush();
                                        $user->setPersona($persona);
                                        $entityManager->persist($user);
                                        $entityManager->flush();
                                        $expediente->setUsuario($user);
                                        $expediente->setGenero($form["genero"]->getData());
                                        $expediente->setFechaNacimiento($form["fechaNacimiento"]->getData());
                                        $expediente->setDireccion($form["direccion"]->getData());
                                        $expediente->setTelefono($form["telefono"]->getData());
                                        $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
                                        $expediente->setEstadoCivil($form["estadoCivil"]->getData());
                                        $expediente->setHabilitado(true);

                                        $inicio = strtoupper($request->request->get('primerApellido')[0])."%".date("Y");
                                        $iniciales = strtoupper($request->request->get('primerApellido')[0]);
                                        
                                        // Obteniendo lista de usuarios
                                        $INICIO_I = $inicio;
                                        $ID_CLINICA_I = -1;
                                        $sql= 'CALL obtener_ultimo_num_expediente(:ID_CLINICA_I, :INICIO_I)';
                                        if (is_null($AuthUser->getUser()->getClinica()))
                                        {
                                            $ID_CLINICA_I = $request->request->get('clinica');
                                        }
                                        else
                                        {
                                            $ID_CLINICA_I = $AuthUser->getUser()->getClinica()->getId();
                                        }
                                        $conn = $this->getDoctrine()->getManager()->getConnection();
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute(array(
                                            'ID_CLINICA_I' => $ID_CLINICA_I,
                                            'INICIO_I' => $INICIO_I
                                        ));
                                        $result= $stmt->fetchAll();
                                        $stmt->closeCursor();

                                        $validador = '';
                                        $calculo = "0001-";

                                        foreach ($result as $value) {
                                            $correlativo = (int) substr($value["expediente"],2,4)+1;
                                            
                                            if( $correlativo <= 9 )
                                            {
                                                $calculo="000".strval($correlativo)."-";
                                            }
                                            elseif ( $correlativo >= 10 && $correlativo <= 99 ) 
                                            {
                                                $calculo="00".strval($correlativo)."-";
                                            }
                                            elseif ( $correlativo >= 100 && $correlativo <= 999 ) 
                                            {
                                                $calculo="0".strval($correlativo)."-";
                                            }
                                            else{
                                                $calculo=strval($correlativo)."-";
                                            }
                                        }

                                        $validador = $iniciales.$calculo.date("Y");
                                        

                                        $NUM_EXPEDIENTE = $validador;
                                        $ID_CLINICA_I = -1;
                                        $sql= 'CALL comprobar_num_expediente(:ID_CLINICA_I, :NUM_EXPEDIENTE)';
                                        if (is_null($AuthUser->getUser()->getClinica()))
                                        {
                                            $ID_CLINICA_I = $request->request->get('clinica');
                                        }
                                        else
                                        {
                                            $ID_CLINICA_I = $AuthUser->getUser()->getClinica()->getId();
                                        }
                                        $conn = $this->getDoctrine()->getManager()->getConnection();
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute(array(
                                            'ID_CLINICA_I' => $ID_CLINICA_I,
                                            'NUM_EXPEDIENTE' => $NUM_EXPEDIENTE
                                        ));
                                        $result= $stmt->fetchAll();
                                        $stmt->closeCursor();

                                        if($result == null){
                                            $expediente->setNumeroExpediente($iniciales.$calculo.date("Y"));
                                        }else{
                                            $this->addFlash('fail','Error el expediente ya existe');
                                            return $this->render('expediente/new.html.twig', [
                                                'expediente' => $expediente,
                                                'clinicas'   => $clinicas,
                                                'pertenece'  => $clinicaPerteneciente,
                                                'editar'     => $editar,
                                                'form'       => $form->createView(),
                                            ]);
                                        }

                                        $entityManager->persist($expediente);
                                        $entityManager->flush();
                                        $this->addFlash('success', 'Paciente añadido con éxito');

                                        // Generando vista de expediente
                                        $ID_EXPEDIENTE_I = $expediente->getId();
                                        $sql= 'CALL crear_vista_expediente(:ID_EXPEDIENTE_I)';
                                        $conn = $this->getDoctrine()->getManager()->getConnection();
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute(array(
                                            'ID_EXPEDIENTE_I' => $ID_EXPEDIENTE_I 
                                        ));
                                        $stmt->closeCursor();
                                        return $this->redirectToRoute('expediente_index');
                                        //FIN PROCESO DE DATOS
                                    }else{
                                        $this->addFlash('fail', 'Error el género del paciente no puede estar vacío');
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error la fecha de nacimiento del paciente no puede estar vacía');
                                }
                            }else{
                                $this->addFlash('fail', 'Error el teléfono de contacto del paciente no puede estar vacío');
                            }
                        }else{
                            $this->addFlash('fail', 'Error la dirección del paciente no puede estar vacía');
                        }
                    }else{
                        $this->addFlash('fail', 'Error el email del paciente no puede estar vacío');
                    }
                }else{
                    $this->addFlash('fail', 'Error los apellidos del paciente no pueden estar vacíos');
                }
            }else{
                $this->addFlash('fail', 'Error los nombres del paciente no pueden estar vacíos');
            }
        }
        return $this->render('expediente/new.html.twig', [
            'expediente' => $expediente,
            'clinicas'   => $clinicas,
            'pertenece'  => $clinicaPerteneciente,
            'editar'     => $editar,
            'form'       => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="expediente_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_EXPEDIENTE')")
     */
    public function show(Expediente $expediente, Security $AuthUser): Response
    {
        //OBTENER SI EL PACIENTE SE ENCUENTRA INGRESADO
        $ID_EXPEDIENTE_I = $expediente->getId();
        $sql= 'CALL obtener_ingreso_clinica_paciente(:ID_EXPEDIENTE_I)';
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'ID_EXPEDIENTE_I' => $ID_EXPEDIENTE_I 
        ));
        $ingresoActual = $stmt->fetch();
        $stmt->closeCursor();

        //OBTENER VIEW DE EXPEDIENTE
        $ID_EXPEDIENTE_I = $expediente->getId();
        $sql= 'CALL obtener_vista_expediente(:ID_EXPEDIENTE_I)';
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'ID_EXPEDIENTE_I' => $ID_EXPEDIENTE_I 
        ));
        $result= $stmt->fetch();
        $stmt->closeCursor();

        //VALIDACION PARA SABER SI EL EXPEDIENTE PERTENECE A LA CLINICA DEL USUARIO, EN EL CASO QUE NO SEA SUPER ADMIN
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    return $this->render('expediente/show.html.twig', [
                        'ingresado'  => $ingresoActual,
                        'expediente' => $result,
                        'user'              => $AuthUser,


                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail', 'Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            return $this->render('expediente/show.html.twig', [
                'ingresado'  => $ingresoActual,
                'expediente' => $result,
                'user'       => $AuthUser,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{id}/edit", name="expediente_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_EXPEDIENTE')")
     */
    public function edit(Request $request, Expediente $expediente,Security $AuthUser): Response
    {   
        $persona = $this->getDoctrine()->getRepository(Persona::class)->find($expediente->getUsuario()->getPersona()->getId());

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() != $expediente->getUsuario()->getClinica()->getId()){
                $this->addFlash('fail', 'Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }

        if (!$expediente->getHabilitado()){
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }

        $editar = true;
        $expediente->email = $expediente->getUsuario()->getEmail();
        $form = $this->createFormBuilder($expediente)
        ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
        ->add('direccion',TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('telefono',TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('apellidoCasada',TextType::class, array( 'required' => false, 'attr' => array('class' => 'form-control')))
        ->add('estadoCivil',TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('genero', EntityType::class, array('class' => Genero::class,
            'placeholder' => 'Seleccione el genero', 
            'choice_label' => 'descripcion', 
            'attr' => array('class' => 'form-control') ))
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Obteniendo si el email insertado, lo posee otro usuario actualmente
            $ID_USUARIO_I = $expediente->getUsuario()->getId();
            $EMAIL_I = $form["email"]->getData();
            $sql= 'SELECT email_duplicado(:ID_USUARIO_I, :EMAIL_I) AS resultado';
            $conn = $this->getDoctrine()->getManager()->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute(array(
                'ID_USUARIO_I' => $ID_USUARIO_I , 
                'EMAIL_I' => $EMAIL_I 
            ));
            $result = $stmt->fetch();
            $stmt->closeCursor();

            if ($result['resultado'] == 1)
            {
                $this->addFlash('fail', 'Usuario con este email ya existe');
                return $this->render('expediente/edit.html.twig',[
                        'expediente' => $expediente,
                        'form' => $form->createView(),
                        'editar' => $editar,
                        'persona'    => $persona,
                        ]);
            }

            if(!empty($request->request->get('nueva_password')) || !empty($request->request->get('nueva_confirmPassword'))){
                if(preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",$request->request->get('nueva_password')) && preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",$request->request->get('nueva_confirmPassword')) ){
                    if ($request->request->get('nueva_password') != $request->request->get('nueva_confirmPassword') ) {
                        $this->addFlash('fail', 'ambas contraseñas deben coincidir');
                        return $this->render('expediente/edit.html.twig',[
                            'expediente' => $expediente,
                            'form' => $form->createView(),
                            'persona'    => $persona,
                            'editar' => $editar,
                            ]);
                    }
                }else{
                    $this->addFlash('fail','La contraseña debe tener al entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula, al menos una mayúscula y al menos un caracter no alfanumérico.');
                    return $this->render('expediente/edit.html.twig',[
                        'expediente' => $expediente,
                        'form' => $form->createView(),
                        'persona'    => $persona,
                        'editar' => $editar,
                        ]);
                }
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $user = $expediente->getUsuario();


            $persona->setPrimerNombre('primerNombre');
            $persona->setSegundoNombre('segundoNombre');
            $entityManager->persist($persona);
            $entityManager->flush();

            $persona->setPrimerNombre($request->request->get('primerNombre'));
            $persona->setSegundoNombre($request->request->get('segundoNombre'));


            $user->setEmail('correo');
            $entityManager->persist($user);
            $entityManager->flush();

            $user->setEmail($form["email"]->getData());

            if(!empty($request->request->get('nueva_password'))){
                $user->setPassword(password_hash($request->request->get('nueva_password'),PASSWORD_DEFAULT,[15]));
            }

            $entityManager->persist($persona);
            $entityManager->flush();
            $entityManager->persist($user);
            $entityManager->flush();

            $expediente->setDireccion("santaana");
            $entityManager->persist($expediente);
            $entityManager->flush();

            $expediente->setGenero($form["genero"]->getData());
            $expediente->setDireccion($form["direccion"]->getData());
            $expediente->setTelefono($form["telefono"]->getData());
            $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
            $expediente->setEstadoCivil($form["estadoCivil"]->getData());
            $entityManager->persist($expediente);
            $entityManager->flush();
            $this->addFlash('success', 'Paciente modificado con éxito');
            return $this->redirectToRoute('expediente_index');
        }
        
        return $this->render('expediente/edit.html.twig', [
            'expediente' => $expediente,
            'editar'     => $editar,
            'persona'    => $persona,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="expediente_delete", methods={"DELETE"})
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_EXPEDIENTE')")
     */
    public function delete(Request $request, Expediente $expediente, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() != $expediente->getUsuario()->getClinica()->getId()){
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        } 
        if ($this->isCsrfTokenValid('delete'.$expediente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $expediente->setHabilitado(false);
            $entityManager->persist($expediente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('expediente_index');
    }


    /**
     * @Route("/{id}/habilitar", name="expediente_habilitar", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_EXPEDIENTE')")
     */
    public function habilitar(Request $request, Expediente $expediente): Response
    {
        if ($this->isCsrfTokenValid('habilitar'.$expediente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $expediente->setHabilitado(true);
            $entityManager->persist($expediente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('expediente_index');
    }

    /**
     * @Route("/{id}/historico", name="expediente_historial", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_EXPEDIENTE')")
     */
    public function historialIngreso(Request $request, Expediente $expediente, Security $AuthUser): Response
    {
        //VALIDACION PARA INGRESAR SOLO A EXPEDIENTES DE LA CLINICA A EXCEPCION DE ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() != $expediente->getUsuario()->getClinica()->getId()){
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        }

        // Obteniendo historial de ingresado
        $ID_EXPEDIENTE_I = $expediente->getId();
        $sql= 'CALL obtener_historial_ingreso(:ID_EXPEDIENTE_I)';
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            'ID_EXPEDIENTE_I' => $ID_EXPEDIENTE_I
        ));
        $historialIngresado = $stmt->fetchAll();
        $stmt->closeCursor();
        
        return $this->render('expediente/historial.html.twig', [
                'expediente' => $expediente,
                'historiales' => $historialIngresado,
            ]);


    }
}
