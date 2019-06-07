<?php

namespace App\Controller;

use App\Entity\Expediente;
use App\Entity\Clinica;
use App\Entity\Genero;
use App\Entity\Rol;
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
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_EXPEDIENTE')")
     */
    public function index(ExpedienteRepository $expedienteRepository,Security $AuthUser): Response
    {
        $em = $this->getDoctrine()->getManager();
        if($AuthUser->getUser()->getRol()->getNombreRol() == 'ROLE_SA'){
            $RAW_QUERY = 'SELECT CONCAT(p.primer_nombre," ",IFNULL(p.segundo_nombre," ")," ",p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo, e.numero_expediente as expediente,e.id,e.habilitado,c.nombre_clinica FROM `user` as u,expediente as e,clinica c, `persona` as p WHERE u.id = e.usuario_id AND u.clinica_id = c.id AND p.id = u.persona_id;';

        }else{
             $RAW_QUERY = 'SELECT CONCAT(p.primer_nombre," ",IFNULL(p.segundo_nombre," ")," ",p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo, e.numero_expediente as expediente,e.id,e.habilitado,c.nombre_clinica FROM `user` as u,expediente as e,clinica as c, `persona` as p WHERE u.id = e.usuario_id AND p.id = u.persona_id AND u.clinica_id = c.id AND clinica_id='.$AuthUser->getUser()->getClinica()->getId().';'; 
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
     * @Security2("is_granted('ROLE_PERMISSION_NEW_EXPEDIENTE')")
     */
    public function new(Request $request, Security $AuthUser): Response
    {
        $editar = false;
        $expediente = new Expediente();
        $clinicaPerteneciente = $AuthUser->getUser()->getClinica();

        if(is_null($AuthUser->getUser()->getClinica())){
            $clinicas = $this->getDoctrine()->getRepository(Clinica::class)->findAll();
        }else{
            $clinicas=null;
        }
        $form = $this->createFormBuilder($expediente)
            ->add('nombres', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
            ->add('direccion',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('fechaNacimiento', DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['class' => 'form-control']])
            ->add('telefono',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('apellidoCasada',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('estadoCivil',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('genero', EntityType::class, array('class' => Genero::class, 'placeholder' => 'Seleccione el genero', 'choice_label' => 'descripcion', 'attr' => array('class' => 'form-control') ))
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form["nombres"]->getData() != ""){
                if($request->request->get("apellidos") != ""){
                    if($form["email"]->getData() != ""){
                        if($form["direccion"]->getData() != ""){
                            if($form["telefono"]->getData() != ""){
                                if($form["fechaNacimiento"]->getData() != ""){
                                    if($form["genero"]->getData() != ""){
                                        //INICIO DE PROCESO DE DATOS
                                        $valido = true;
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $usuario = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $form["email"]->getData()]);
                                        if (count($usuario) > 0)
                                        {
                                            $valido = false;
                                            $this->addFlash('fail', 'Usuario con este email ya existe');
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
	                                            $user->setNombres($form["nombres"]->getData());
	                                            $user->setApellidos($request->request->get('apellidos'));
	                                            $user->setEmail($form["email"]->getData());
	                                            $user->setClinica($this->getDoctrine()->getRepository(Clinica::class)->find($request->request->get('clinica')));
	                                            $user->setRol($this->getDoctrine()->getRepository(Rol::class)->findOneByNombre('ROLE_PACIENTE'));
	                                            $user->setPassword(password_hash($request->request->get('password'),PASSWORD_DEFAULT,[15]));
	                                            $user->setIsActive(true);
	                                            $entityManager->persist($user);
	                                            $expediente->setUsuario($user);
	                                            $expediente->setGenero($form["genero"]->getData());
	                                            $expediente->setFechaNacimiento($form["fechaNacimiento"]->getData());
	                                            $expediente->setDireccion($form["direccion"]->getData());
	                                            $expediente->setTelefono($form["telefono"]->getData());
	                                            $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
	                                            $expediente->setEstadoCivil($form["estadoCivil"]->getData());
	                                            $expediente->setHabilitado(true);   
	                                            $inicio = strtoupper($request->request->get('apellidos')[0])."%".date("Y");
	                                            $iniciales = strtoupper($request->request->get('apellidos')[0]);
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
                                        		$this->addFlash('fail', 'Error, debe elegir la clinica a la cual desea asignar este paciente.');
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
                                            $user->setNombres($form["nombres"]->getData());
                                            $user->setApellidos($request->request->get('apellidos'));
                                            $user->setEmail($form["email"]->getData());
                                            $user->setClinica($this->getDoctrine()->getRepository(Clinica::class)->find($AuthUser->getUser()->getClinica()->getId()));
                                            $user->setRol($this->getDoctrine()->getRepository(Rol::class)->findOneByNombre('ROLE_PACIENTE'));
                                            $user->setPassword(password_hash($request->request->get('password'),PASSWORD_DEFAULT,[15]));
                                            $user->setIsActive(true);
                                            $entityManager->persist($user);
                                            $expediente->setUsuario($user);
                                            $expediente->setGenero($form["genero"]->getData());
                                            $expediente->setFechaNacimiento($form["fechaNacimiento"]->getData());
                                            $expediente->setDireccion($form["direccion"]->getData());
                                            $expediente->setTelefono($form["telefono"]->getData());
                                            $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
                                            $expediente->setEstadoCivil($form["estadoCivil"]->getData());
                                            $expediente->setHabilitado(true);
                                            $inicio = strtoupper($request->request->get('apellidos')[0])."%".date("Y");
                                            $iniciales = strtoupper($request->request->get('apellidos')[0]);
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
                                        //FIN PROCESO DE DATOS
                                    }else{
                                        $this->addFlash('fail', 'Error el genero del paciente no puede estar vacio');
                                        return $this->render('expediente/new.html.twig', [
                                            'expediente' => $expediente,
                                            'clinicas'   => $clinicas,
                                            'pertenece'  => $clinicaPerteneciente,
                                            'editar'     => $editar,
                                            'form'       => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error la fecha de nacimiento del paciente no puede estar vacia');
                                    return $this->render('expediente/new.html.twig', [
                                        'expediente' => $expediente,
                                        'clinicas'   => $clinicas,
                                        'pertenece'  => $clinicaPerteneciente,
                                        'editar'     => $editar,
                                        'form'       => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error el telefono de contacto del paciente no puede estar vacio');
                                return $this->render('expediente/new.html.twig', [
                                    'expediente' => $expediente,
                                    'clinicas'   => $clinicas,
                                    'pertenece'  => $clinicaPerteneciente,
                                    'editar'     => $editar,
                                    'form'       => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error la direccion del paciente no puede estar vacia');
                            return $this->render('expediente/new.html.twig', [
                                'expediente' => $expediente,
                                'clinicas'   => $clinicas,
                                'pertenece'  => $clinicaPerteneciente,
                                'editar'     => $editar,
                                'form'       => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error el email del paciente no puede estar vacio');
                        return $this->render('expediente/new.html.twig', [
                            'expediente' => $expediente,
                            'clinicas'   => $clinicas,
                            'pertenece'  => $clinicaPerteneciente,
                            'editar'     => $editar,
                            'form'       => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error los apellidos del paciente no pueden estar vacios');
                    return $this->render('expediente/new.html.twig', [
                        'expediente' => $expediente,
                        'clinicas'   => $clinicas,
                        'pertenece'  => $clinicaPerteneciente,
                        'editar'     => $editar,
                        'form'       => $form->createView(),
                    ]);
                }
            }else{
                $this->addFlash('fail', 'Error los nombres del paciente no pueden estar vacios');
                return $this->render('expediente/new.html.twig', [
                    'expediente' => $expediente,
                    'clinicas'   => $clinicas,
                    'pertenece'  => $clinicaPerteneciente,
                    'editar'     => $editar,
                    'form'       => $form->createView(),
                ]);
            }
            $this->addFlash('success', 'Paciente añadido con exito');
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
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_EXPEDIENTE')")
     */
    public function show(Expediente $expediente, Security $AuthUser): Response
    {   
        //VALIDACION DE BLOQUEO DE RUTAS EN CASO DE INTENTAR ACCEDER A REGISTROS DE OTRAS CLINICAS SI NO ES ROLE_SA
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY="SELECT * FROM `ingresado` WHERE fecha_salida IS NULL AND expediente_id =".$expediente->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $ingresoActual = $statement->fetchAll();

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    return $this->render('expediente/show.html.twig', [
                        'expediente' => $expediente,
                        'ingresado'  => $ingresoActual,
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail', 'Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            return $this->render('expediente/show.html.twig', [
                'expediente' => $expediente,
                'ingresado'  => $ingresoActual,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{id}/edit", name="expediente_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_EXPEDIENTE')")
     */
    public function edit(Request $request, Expediente $expediente,Security $AuthUser): Response
    {   
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    $editar = true;
                    $expediente->nombres = $expediente->getUsuario()->getNombres();
                    $expediente->email = $expediente->getUsuario()->getEmail();
                    $form = $this->createFormBuilder($expediente)
                    ->add('nombres', TextType::class, array('attr' => array('class' => 'form-control')))
                    ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
                    ->add('direccion',TextType::class, array('attr' => array('class' => 'form-control')))
                    ->add('fechaNacimiento', DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['class' => 'form-control']])
                    ->add('telefono',TextType::class, array('attr' => array('class' => 'form-control')))
                    ->add('apellidoCasada',TextType::class, array('attr' => array('class' => 'form-control')))
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
                                    ]);
                        }
                        if(!empty($request->request->get('nueva_password')) || !empty($request->request->get('nueva_confirmPassword'))){
                                if ($request->request->get('nueva_password') == $request->request->get('nueva_confirmPassword') ) {
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $user = $expediente->getUsuario();
                                    $user->setNombres($form["nombres"]->getData());
                                    $user->setEmail($form["email"]->getData());
                                    $user->setPassword(password_hash($request->request->get('nueva_password'),PASSWORD_DEFAULT,[15]));
                                    $entityManager->persist($user);
                                    $expediente->setGenero($form["genero"]->getData());
                                    $expediente->setDireccion($form["direccion"]->getData());
                                    $expediente->setTelefono($form["telefono"]->getData());
                                    $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
                                    $expediente->setEstadoCivil($form["estadoCivil"]->getData());
                                    $entityManager->persist($expediente);
                                    $entityManager->flush();
                                    $this->addFlash('success', 'Paciente modificado con exito');
                                    return $this->redirectToRoute('expediente_index');
                                }else{
                                    $this->addFlash('fail', 'ambas contraseñas deben coincidir');
                                    return $this->render('expediente/edit.html.twig',[
                                    'expediente' => $expediente,
                                    'form' => $form->createView(),
                                    'editar' => $editar,
                                    ]);
                                }
                            }else{
                                $entityManager = $this->getDoctrine()->getManager();
                                $user = $expediente->getUsuario();
                                $user->setNombres($form["nombres"]->getData());
                                $user->setEmail($form["email"]->getData());
                                $entityManager->persist($user);
                                $expediente->setGenero($form["genero"]->getData());
                                $expediente->setDireccion($form["direccion"]->getData());
                                $expediente->setTelefono($form["telefono"]->getData());
                                $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
                                $expediente->setEstadoCivil($form["estadoCivil"]->getData());
                                $entityManager->persist($expediente);
                                $entityManager->flush();
                                $this->addFlash('success', 'Paciente modificado con exito');
                                return $this->redirectToRoute('expediente_index');
                            }
                    }
                    return $this->render('expediente/edit.html.twig', [
                        'expediente' => $expediente,
                        'editar'     => $editar,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail', 'Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            $editar = true;
            $expediente->nombres = $expediente->getUsuario()->getNombres();
            $expediente->email = $expediente->getUsuario()->getEmail();
            $form = $this->createFormBuilder($expediente)
            ->add('nombres', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
            ->add('direccion',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('fechaNacimiento', DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['class' => 'form-control']])
            ->add('telefono',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('apellidoCasada',TextType::class, array('attr' => array('class' => 'form-control')))
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
                            ]);
                }
                if(!empty($request->request->get('nueva_password')) || !empty($request->request->get('nueva_confirmPassword'))){
                        if ($request->request->get('nueva_password') == $request->request->get('nueva_confirmPassword') ) {
                            $entityManager = $this->getDoctrine()->getManager();
                            $user = $expediente->getUsuario();
                            $user->setNombres($form["nombres"]->getData());
                            $user->setEmail($form["email"]->getData());
                            $user->setPassword(password_hash($request->request->get('nueva_password'),PASSWORD_DEFAULT,[15]));
                            $entityManager->persist($user);
                            $expediente->setGenero($form["genero"]->getData());
                            $expediente->setDireccion($form["direccion"]->getData());
                            $expediente->setTelefono($form["telefono"]->getData());
                            $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
                            $expediente->setEstadoCivil($form["estadoCivil"]->getData());
                            $entityManager->persist($expediente);
                            $entityManager->flush();
                            $this->addFlash('success', 'Paciente modificado con exito');
                            return $this->redirectToRoute('expediente_index');
                        }else{
                            $this->addFlash('fail', 'ambas contraseñas deben coincidir');
                            return $this->render('expediente/edit.html.twig',[
                            'expediente' => $expediente,
                            'form' => $form->createView(),
                            'editar' => $editar,
                            ]);
                        }
                    }else{
                        $entityManager = $this->getDoctrine()->getManager();
                        $user = $expediente->getUsuario();
                        $user->setNombres($form["nombres"]->getData());
                        $user->setEmail($form["email"]->getData());
                        $entityManager->persist($user);
                        $expediente->setGenero($form["genero"]->getData());
                        $expediente->setDireccion($form["direccion"]->getData());
                        $expediente->setTelefono($form["telefono"]->getData());
                        $expediente->setApellidoCasada($form["apellidoCasada"]->getData());
                        $expediente->setEstadoCivil($form["estadoCivil"]->getData());
                        $entityManager->persist($expediente);
                        $entityManager->flush();
                        $this->addFlash('success', 'Paciente modificado con exito');
                        return $this->redirectToRoute('expediente_index');
                    }
            }
            return $this->render('expediente/edit.html.twig', [
                'expediente' => $expediente,
                'editar'     => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{id}", name="expediente_delete", methods={"DELETE"})
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
}
