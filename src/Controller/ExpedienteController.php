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
        $em = $this->getDoctrine()->getManager();
        if($AuthUser->getUser()->getRol()->getNombreRol() == 'ROLE_SA'){
            $RAW_QUERY = 'SELECT CONCAT(p.primer_nombre," ",IFNULL(p.segundo_nombre," ")," ",p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo, e.numero_expediente as expediente,e.id as id,e.habilitado,c.nombre_clinica FROM `user` as u,expediente as e,clinica c, `persona` as p WHERE u.id = e.usuario_id AND u.clinica_id = c.id AND p.id = u.persona_id;';

        }else{
             $RAW_QUERY = 'SELECT CONCAT(p.primer_nombre," ",IFNULL(p.segundo_nombre," ")," ",p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo, e.numero_expediente as expediente,e.id as id,e.habilitado,c.nombre_clinica FROM `user` as u,expediente as e,clinica as c, `persona` as p WHERE u.id = e.usuario_id AND p.id = u.persona_id AND u.clinica_id = c.id AND clinica_id='.$AuthUser->getUser()->getClinica()->getId().';'; 
        }
       

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
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
                                        if(is_null($AuthUser->getUser()->getClinica()) && $valido){
                                            if($request->request->get('clinica') != ""){
                                            	$user = new User();
	                                            $persona->setPrimerNombre($request->request->get('primerNombre'));
                                                $persona->setSegundoNombre($request->request->get('segundoNombre'));
                                                $persona->setPrimerApellido($request->request->get('primerApellido'));
                                                $persona->setSegundoApellido($request->request->get('segundoApellido'));
	                                            $user->setEmail($form["email"]->getData());
	                                            $user->setClinica($this->getDoctrine()->getRepository(Clinica::class)->find($request->request->get('clinica')));
	                                            $user->setRol($this->getDoctrine()->getRepository(Rol::class)->findOneByNombre('ROLE_PACIENTE'));
	                                            $user->setPassword(password_hash($request->request->get('password'),PASSWORD_DEFAULT,[15]));
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
	                                            $string = "SELECT e.numero_expediente as expediente FROM expediente as e WHERE e.id 
	                                            IN (SELECT MAX(exp.id) FROM expediente as exp, user as u 
	                                            WHERE u.id = exp.usuario_id AND u.clinica_id =".$request->request->get('clinica')." AND exp.numero_expediente LIKE '".$inicio."')";
	                                            $statement = $entityManager->getConnection()->prepare($string);
	                                            $statement->execute();
	                                            $result = $statement->fetchAll();
	                                            if($result != NULL){
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
	                                                $RAW_QUERY="SELECT e.numero_expediente FROM expediente as e,user as u WHERE e.usuario_id = u.id AND u.clinica_id =".$request->request->get('clinica')." 
	                                                    AND numero_expediente='".$validador."';";
	                                                $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
	                                                $statement->execute();
	                                                $result = $statement->fetchAll();
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
	                                            }else{
	                                                $validador = $iniciales."0001-".date("Y");
	                                                $RAW_QUERY="SELECT e.numero_expediente FROM expediente as e,user as u WHERE e.usuario_id = u.id AND u.clinica_id =".$request->request->get('clinica')." 
	                                                    AND numero_expediente='".$validador."';";
	                                                $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
	                                                $statement->execute();
	                                                $result = $statement->fetchAll();
	                                                if($result == null){
	                                                    $expediente->setNumeroExpediente($iniciales."0001-".date("Y"));
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
	                                            }
                                            }else{
                                        		$this->addFlash('fail', 'Error, debe elegir la clínica a la cual desea asignar este paciente.');
                                        		return $this->render('expediente/new.html.twig', [
                                                    'expediente' => $expediente,
                                                    'clinicas'   => $clinicas,
                                                    'pertenece'  => $clinicaPerteneciente,
                                                    'editar'     => $editar,
                                                    'form'       => $form->createView(),
                                                ]);
                                            }
                                        }elseif($valido){
                                            $user = new User();
                                            $persona->setPrimerNombre($request->request->get('primerNombre'));
                                            $persona->setSegundoNombre($request->request->get('segundoNombre'));
                                            $persona->setPrimerApellido($request->request->get('primerApellido'));
                                            $persona->setSegundoApellido($request->request->get('segundoApellido'));
                                            $user->setEmail($form["email"]->getData());
                                            $user->setClinica($this->getDoctrine()->getRepository(Clinica::class)->find($AuthUser->getUser()->getClinica()->getId()));
                                            $user->setRol($this->getDoctrine()->getRepository(Rol::class)->findOneByNombre('ROLE_PACIENTE'));
                                            $user->setPassword(password_hash($request->request->get('password'),PASSWORD_DEFAULT,[15]));
                                            $user->setIsActive(true); 
                                            $entityManager->persist($persona);
                                            $user->setPersona($persona);
                                            $entityManager->persist($user);
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
                                            $string = "SELECT e.numero_expediente as expediente FROM expediente as e WHERE e.id 
                                            IN (SELECT MAX(exp.id) FROM expediente as exp, user as u 
                                            WHERE u.id = exp.usuario_id AND u.clinica_id =".$AuthUser->getUser()->getClinica()->getId()." AND exp.numero_expediente LIKE '".$inicio."')";
                                            $statement = $entityManager->getConnection()->prepare($string);
                                            $statement->execute();
                                            $result = $statement->fetchAll();
                                            if($result != NULL){
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
                                                $RAW_QUERY="SELECT e.numero_expediente FROM expediente as e,user as u WHERE e.usuario_id = u.id AND u.clinica_id =".$AuthUser->getUser()->getClinica()->getId()." AND numero_expediente='".$validador."';";
                                                $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
                                                $statement->execute();
                                                $result = $statement->fetchAll();
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
                                            }else{
                                                $validador = $iniciales."0001-".date("Y");
                                                $RAW_QUERY="SELECT e.numero_expediente FROM expediente as e,user as u WHERE e.usuario_id = u.id AND u.clinica_id =".$request->request->get('clinica')." AND numero_expediente='".$validador."';";
                                                $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
                                                $statement->execute();
                                                $result = $statement->fetchAll();
                                                if(is_null($result)){
                                                    $expediente->setNumeroExpediente($iniciales."0001-".date("Y"));
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
                                            }
                                        }
                                        $entityManager->persist($expediente);
                                        $entityManager->flush();

                                        //GENERACION DE VISTA AL CREAR UN PACIENTE CON EXITO PARA EL SHOW
                                        $conn = $entityManager->getConnection();
                                        $sql='CREATE VIEW expediente_paciente_'.$expediente->getId().' AS
                                        SELECT ex.id as id_expediente,DATE(ex.fecha_nacimiento) as fecha_nacimiento_expediente, ( SELECT getEdad('.$expediente->getId().') ) as edad_expediente, ex.direccion as direccion_expediente,ex.estado_civil as estado_civil_expediete,ex.apellido_casada as apellido_casada_expediente, ex.creado_en as creado, ex.actualizado_en as actualizado, ex.numero_expediente as numero_expediente,ex.telefono as telefono_expediente, gen.descripcion as genero, u.email as correo_electronico, CONCAT(p.primer_nombre," ",IFNULL(p.segundo_nombre," ")," ",p.primer_apellido," ",IFNULL(p.segundo_apellido, " ") ) as nombre_completo
                                        FROM expediente as ex,genero as gen, user as u, persona as p
                                        WHERE 
                                        ex.genero_id = gen.id       AND
                                        ex.usuario_id = u.id        AND
                                        u.persona_id = p.id         AND
                                        ex.id='.$expediente->getId().';';
                                        $view = $conn->prepare($sql);
                                        $view->execute();
                                        //FIN PROCESO DE DATOS
                                    }else{
                                        $this->addFlash('fail', 'Error el género del paciente no puede estar vacío');
                                        return $this->render('expediente/new.html.twig', [
                                            'expediente' => $expediente,
                                            'clinicas'   => $clinicas,
                                            'pertenece'  => $clinicaPerteneciente,
                                            'editar'     => $editar,
                                            'form'       => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error la fecha de nacimiento del paciente no puede estar vacía');
                                    return $this->render('expediente/new.html.twig', [
                                        'expediente' => $expediente,
                                        'clinicas'   => $clinicas,
                                        'pertenece'  => $clinicaPerteneciente,
                                        'editar'     => $editar,
                                        'form'       => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error el teléfono de contacto del paciente no puede estar vacío');
                                return $this->render('expediente/new.html.twig', [
                                    'expediente' => $expediente,
                                    'clinicas'   => $clinicas,
                                    'pertenece'  => $clinicaPerteneciente,
                                    'editar'     => $editar,
                                    'form'       => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error la dirección del paciente no puede estar vacía');
                            return $this->render('expediente/new.html.twig', [
                                'expediente' => $expediente,
                                'clinicas'   => $clinicas,
                                'pertenece'  => $clinicaPerteneciente,
                                'editar'     => $editar,
                                'form'       => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error el email del paciente no puede estar vacío');
                        return $this->render('expediente/new.html.twig', [
                            'expediente' => $expediente,
                            'clinicas'   => $clinicas,
                            'pertenece'  => $clinicaPerteneciente,
                            'editar'     => $editar,
                            'form'       => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error los apellidos del paciente no pueden estar vacíos');
                    return $this->render('expediente/new.html.twig', [
                        'expediente' => $expediente,
                        'clinicas'   => $clinicas,
                        'pertenece'  => $clinicaPerteneciente,
                        'editar'     => $editar,
                        'form'       => $form->createView(),
                    ]);
                }
            }else{
                $this->addFlash('fail', 'Error los nombres del paciente no pueden estar vacíos');
                return $this->render('expediente/new.html.twig', [
                    'expediente' => $expediente,
                    'clinicas'   => $clinicas,
                    'pertenece'  => $clinicaPerteneciente,
                    'editar'     => $editar,
                    'form'       => $form->createView(),
                ]);
            }
            $this->addFlash('success', 'Paciente añadido con éxito');
            return $this->redirectToRoute('expediente_index');
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
        //CONOCER SI EL PACIENTE ESTA INGRESADO
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY="SELECT * FROM `ingresado` WHERE fecha_salida IS NULL AND expediente_id =".$expediente->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $ingresoActual = $statement->fetch();

        //USO DE VIEW
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = 'SELECT * FROM expediente_paciente_'.$expediente->getId()
            ;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result= $stmt->fetch();

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
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    $editar = true;
                    $expediente->email = $expediente->getUsuario()->getEmail();
                    $form = $this->createFormBuilder($expediente)
                    ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
                    ->add('direccion',TextType::class, array('attr' => array('class' => 'form-control')))
                    ->add('telefono',TextType::class, array('attr' => array('class' => 'form-control')))
                    ->add('apellidoCasada',TextType::class, array( 'required' => false,'attr' => array('class' => 'form-control')))
                    ->add('estadoCivil',TextType::class, array('attr' => array('class' => 'form-control')))
                    ->add('genero', EntityType::class, array('class' => Genero::class, 'placeholder' => 'Seleccione el genero', 'choice_label' => 'descripcion', 'attr' => array('class' => 'form-control') ))
                    ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
                    ->getForm();

                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $RAW_QUERY="SELECT id FROM `user` WHERE id !=".$expediente->getUsuario()->getId()." AND  email='".$form["email"]->getData()."';";
                        $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
                        $statement->execute();
                        $usuario = $statement->fetchAll();
                        if (count($usuario) > 0)
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
                                if ($request->request->get('nueva_password') == $request->request->get('nueva_confirmPassword') ) {
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
                                    $user->setPassword(password_hash($request->request->get('nueva_password'),PASSWORD_DEFAULT,[15]));


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
                                }else{
                                    $this->addFlash('fail', 'ambas contraseñas deben coincidir');
                                    return $this->render('expediente/edit.html.twig',[
                                    'expediente' => $expediente,
                                    'form' => $form->createView(),
                                    'persona'    => $persona,
                                    'editar' => $editar,
                                    ]);
                                }
                            }else{
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
                                $user->setPassword(password_hash($request->request->get('nueva_password'),PASSWORD_DEFAULT,[15]));


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
                    }
                    return $this->render('expediente/edit.html.twig', [
                        'expediente' => $expediente,
                        'editar'     => $editar,
                        'persona'    => $persona,

                        'form' => $form->createView(),
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
            $editar = true;
            $expediente->email = $expediente->getUsuario()->getEmail();
            $form = $this->createFormBuilder($expediente)
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
            ->add('direccion',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('telefono',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('apellidoCasada',TextType::class, array( 'required' => false,'attr' => array('class' => 'form-control')))
            ->add('estadoCivil',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('genero', EntityType::class, array('class' => Genero::class, 'placeholder' => 'Seleccione el genero', 'choice_label' => 'descripcion', 'attr' => array('class' => 'form-control') ))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $RAW_QUERY="SELECT id FROM `user` WHERE id !=".$expediente->getUsuario()->getId()." AND  email='".$form["email"]->getData()."';";
                $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $usuario = $statement->fetchAll();
                if (count($usuario) > 0)
                {
                    $this->addFlash('fail', 'Usuario con este Email ya existe');
                    return $this->render('expediente/edit.html.twig',[
                            'expediente' => $expediente,
                            'form' => $form->createView(),
                            'editar' => $editar,
                            'persona'    => $persona,
                            ]);
                }
                if(!empty($request->request->get('nueva_password')) || !empty($request->request->get('nueva_confirmPassword'))){
                        if ($request->request->get('nueva_password') == $request->request->get('nueva_confirmPassword') ) {
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
                            $user->setPassword(password_hash($request->request->get('nueva_password'),PASSWORD_DEFAULT,[15]));


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
                        }else{
                            $this->addFlash('fail', 'ambas contraseñas deben coincidir');
                            return $this->render('expediente/edit.html.twig',[
                            'expediente' => $expediente,
                            'form' => $form->createView(),
                            'editar' => $editar,
                            'persona'    => $persona,
                            ]);
                        }
                    }else{
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
                        $user->setPassword(password_hash($request->request->get('nueva_password'),PASSWORD_DEFAULT,[15]));


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
            }
            return $this->render('expediente/edit.html.twig', [
                'expediente' => $expediente,
                'persona' => $persona,
                'editar'     => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{id}", name="expediente_delete", methods={"DELETE"})
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_EXPEDIENTE')")
     */
    public function delete(Request $request, Expediente $expediente, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if ($this->isCsrfTokenValid('delete'.$expediente->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $expediente->setHabilitado(false);
                    $entityManager->persist($expediente);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('expediente_index');
            }else{
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

        $entityManager = $this->getDoctrine()->getManager();
        $RAW_QUERY='SELECT CONCAT(p.primer_nombre," ",IFNULL(p.segundo_nombre," ")," ",p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo, h_i.fecha_entrada as fechaEntrada, h_i.fecha_salida as fechaSalida FROM historial_ingresado as h_i, expediente as e,user as u, persona as p WHERE
            h_i.expediente_id = e.id    AND
            h_i.usuario_id = u.id       AND
            u.persona_id = p.id         AND
            e.id='.$expediente->getId();

        $statement = $entityManager->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $historialIngresado = $statement->fetchAll();
        return $this->render('expediente/historial.html.twig', [
                'expediente' => $expediente,
                'historiales' => $historialIngresado,
            ]);


    }
}
