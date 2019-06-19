<?php

namespace App\Controller;

use App\Entity\Expediente;
use App\Entity\Habitacion;
use App\Entity\Sala;
use App\Entity\Clinica;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('base2.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/home", name="home")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     */
    public function home(Security $AuthUser)
    {   
        return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
        'user'            => $AuthUser,
        ]);
    }

    /**
     * @Route("/agenda", name="calendario_trabajo")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     */
    public function calendarioTrabajo(Security $AuthUser)
    {   
        if($AuthUser->getUser()->getRol()->getNombreRol() == 'ROLE_DOCTOR'){
            return $this->render('cita/calendarClinica.html.twig', [
            'controller_name' => 'HomeController',
            'user'            => $AuthUser,
            ]);
        }else{
            $this->addFlash('fail', 'Esta funcion es unicamente para doctores.');
            return $this->redirectToRoute('home');
        }
    }
    /**
     * @Route("/cambioContrasena", name="app_cambio")
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     */
    public function cambio_contrasena(Request $request,Security $AuthUser): Response
    {   
        $userForm = new User();
        $form = $this->createFormBuilder($userForm)
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ( $request->request->get('password_actual') != "" && $request->request->get('nueva_password') != "" && $request->request->get('confirmar_nueva_password') != "" ) {

                if(password_verify($request->request->get('password_actual'), $AuthUser->getUser()->getPassword() ) ){
                    if(preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",$request->request->get('nueva_password')) && preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",$request->request->get('confirmar_nueva_password')) ){
                        if($request->request->get('nueva_password') == $request->request->get('confirmar_nueva_password') ){
                            $user = $this->getDoctrine()->getRepository(User::class)->find($AuthUser->getUser()->getId());
                            $password = $request->request->get('nueva_password');
                            $hash = password_hash($password, PASSWORD_DEFAULT,[15]);
                            $user->setPassword($hash);
                            $entityManager = $this->getDoctrine()->getEntityManager();
                            $entityManager->persist($user);
                            $entityManager->flush();
                            $this->addFlash('success', 'Contraseña modificada con éxito');
                            return $this->redirectToRoute('home');
                        }else{
                            $this->addFlash('fail', 'Un problema ha ocurrido, la nueva contraseña debe coincidir con la confirmacion de contraseña');
                        }
                    }else{
                        $this->addFlash('fail','Un problema ha ocurrido, Mensaje que va a poner palma para la expresion regular');
                    }

                }else{
                    $this->addFlash('fail', 'Un problema ha ocurrido, compruebe que la contraseña actual sea correcta');
                }
            }else{
                $this->addFlash('fail', 'Un problema ha ocurrido verifique si todos los campos han sido completados');
            }
        }

        return $this->render('user/passwordUpdate.html.twig', [
        'controller_name' => 'HomeController',
        'form'            => $form->createView(),
        ]);
    }

    /**
     * @Route("/salaClinica", name="ajax_cargaSalas", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaSalas(Security $AuthUser, Request $request)
    { 
        $expediente = $this->getDoctrine()->getRepository(Expediente::class)->find($request->request->get('expediente'));
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY="SELECT * FROM sala WHERE clinica_id=".$expediente->getUsuario()->getClinica()->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->json($result);
    }

    /**
     * @Route("/habitacionesClinica", name="ajax_cargaHabitaciones", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaHabitaciones(Security $AuthUser, Request $request)
    { 
        $sala = $this->getDoctrine()->getRepository(Sala::class)->find($request->request->get('sala'));
        $expediente = $this->getDoctrine()->getRepository(Expediente::class)->find($request->request->get('expediente'));
        if($sala->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
            $clinica = $this->getDoctrine()->getRepository(Clinica::class)->find($sala->getClinica()->getId());
            $em = $this->getDoctrine()->getManager();
            //SI EL EXPEDIENTE QUE YO LLEVO CON LA SALA QUE YO LLEVO DEBEN COINCIDIR EN EL ID DE LA CLINICA
            $RAW_QUERY="SELECT DISTINCT habitacion.* FROM habitacion,clinica,sala, camilla WHERE
                        camilla.habitacion_id = habitacion.id           AND
                        habitacion.sala_id    = sala.id                 AND
                        sala.clinica_id       = clinica.id              AND
                        clinica.id            = ".$clinica->getId()."   AND
                        sala.id               = ".$sala->getId()."      AND
                        camilla.id NOT IN (
                            SELECT camilla_id FROM ingresado WHERE fecha_salida IS NULL
                        );";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->json($result);
        }else{
            $this->addFlash('fail','Error, este expediente no es valido');
            $this->redirectToRoute('expediente_index');
        }
        
    }


    /**
     * @Route("/camillasClinica", name="ajax_cargaCamillas", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaCamillas(Security $AuthUser, Request $request)
    { 
        $sala = $this->getDoctrine()->getRepository(Sala::class)->find($request->request->get('sala'));
        $expediente = $this->getDoctrine()->getRepository(Expediente::class)->find($request->request->get('expediente'));
        $habitacion = $this->getDoctrine()->getRepository(Habitacion::class)->find($request->request->get('habitacion'));
        if($sala->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $habitacion->getSala()->getId()== $sala->getId()){
            $clinica = $this->getDoctrine()->getRepository(Clinica::class)->find($sala->getClinica()->getId());
            $em = $this->getDoctrine()->getManager();
            //SI EL EXPEDIENTE QUE YO LLEVO CON LA SALA QUE YO LLEVO DEBEN COINCIDIR EN EL ID DE LA CLINICA
            $RAW_QUERY="SELECT DISTINCT camilla.* FROM habitacion,clinica,sala, camilla WHERE
                        camilla.habitacion_id = habitacion.id            AND
                        habitacion.sala_id    = sala.id                  AND
                        sala.clinica_id       = clinica.id               AND
                        clinica.id            = ".$clinica->getId()."    AND
                        sala.id               = ".$sala->getId()."       AND
                        habitacion.id         = ".$habitacion->getId()." AND
                        camilla.id NOT IN (
                            SELECT camilla_id FROM ingresado WHERE fecha_salida IS NULL
                        );";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->json($result);
        }else{
            $this->addFlash('fail','Error, este expediente no es valido');
            $this->redirectToRoute('expediente_index');
        }
        
    }

    /**
     * @Route("/doctoresClinica", name="ajax_cargaDoctores", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaDoctores(Security $AuthUser, Request $request)
    { 

        if($request->request->get('sala')!="") {
            
            if($request->request->get('habitacion')!=""){
                
                if($request->request->get('expediente')!=""){


                    $sala = $this->getDoctrine()->getRepository(Sala::class)->find($request->request->get('sala'));
                    $expediente = $this->getDoctrine()->getRepository(Expediente::class)->find($request->request->get('expediente'));
                    $habitacion = $this->getDoctrine()->getRepository(Habitacion::class)->find($request->request->get('habitacion'));
                    
                    if($sala->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $habitacion->getSala()->getId()== $sala->getId()){

                        if ($request->request->get('emergencia')!=""){
                            if ($request->request->get('emergencia') == 1) {
                                $clinica = $this->getDoctrine()->getRepository(Clinica::class)->find($sala->getClinica()->getId());
                                $em = $this->getDoctrine()->getManager();
                                //SI EL EXPEDIENTE QUE YO LLEVO CON LA SALA QUE YO LLEVO DEBEN COINCIDIR EN EL ID DE LA CLINICA
                                $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo FROM `user` as u ,`rol` as r, `clinica` as c, `persona` as p  WHERE u.clinica_id = c.id AND u.emergencia = 1 AND r.nombre_rol='ROLE_DOCTOR' AND u.persona_id = p.id AND u.rol_id = r.id  and u.clinica_id=".$sala->getClinica()->getId().";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                return $this->json($result);
                                
                            }else{
                                $clinica = $this->getDoctrine()->getRepository(Clinica::class)->find($sala->getClinica()->getId());
                                $em = $this->getDoctrine()->getManager();
                                //SI EL EXPEDIENTE QUE YO LLEVO CON LA SALA QUE YO LLEVO DEBEN COINCIDIR EN EL ID DE LA CLINICA
                                $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo FROM `user` as u ,`rol` as r, `clinica` as c, `persona` as p WHERE u.clinica_id = c.id AND u.planta = 1 AND r.nombre_rol='ROLE_DOCTOR' AND u.persona_id = p.id AND u.rol_id = r.id  and u.clinica_id=".$sala->getClinica()->getId().";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                return $this->json($result);
                            }
                        }
                        else{
                            $this->addFlash('fail','Error, el estado del ingreso no puede estar vacio');
                            $this->redirectToRoute('expediente_index');

                        }
                        


                    }else{
                        $this->addFlash('fail','Error, este expediente no es valido');
                        $this->redirectToRoute('expediente_index');
                    }

                }else{
                    $this->addFlash('fail', 'Error, no se selecciono ningun expediente');
                    $this->redirectToRoute('expediente_index');
                }

            }else{
                $this->addFlash('fail', 'Error, no se selecciono ninguna habitacion');
                $this->redirectToRoute('expediente_index');
            }

        }else{
            $this->addFlash('fail', 'Error, no se selecciono ninguna sala');
            $this->redirectToRoute('expediente_index');
        }     

    }


    /**
     * @Route("/medicoGeneral", name="ajax_cargaGeneral", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaGeneral(Security $AuthUser, Request $request)
    { 
        if(empty($AuthUser->getUser()->getClinica())){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo FROM `user` as u ,`rol` as r, `persona` as p  WHERE u.usuario_especialidades_id IS NULL AND u.emergencia = false AND u.planta = false AND u.persona_id = p.id AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id  and u.clinica_id=".$request->request->get('clinica').";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->json($result);
        }else{
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo FROM `user` as u ,`rol` as r, `persona` as p  WHERE u.usuario_especialidades_id IS NULL AND u.emergencia = false AND u.planta = false AND u.persona_id = p.id AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id  and u.clinica_id=".$AuthUser->getUser()->getClinica()->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->json($result);
        }

    }

    /**
     * @Route("/medicoEspecialidad", name="ajax_cargaEspecialidad", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaEspecialidad(Security $AuthUser, Request $request)
    { 
        if($request->request->get('especialidad')!=""){
            if(empty($AuthUser->getUser()->getClinica())){
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo FROM `user` as u ,`rol` as r, `persona` as p  WHERE u.emergencia = false AND u.planta = false AND u.persona_id = p.id AND u.usuario_especialidades_id =".$request->request->get('especialidad')." AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id AND u.clinica_id=".$request->request->get('clinica').";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                return $this->json($result);
            }else{
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo FROM `user` as u ,`rol` as r, `persona` as p  WHERE u.emergencia = false AND u.planta = false AND u.persona_id = p.id AND u.usuario_especialidades_id =".$request->request->get('especialidad')." AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id AND u.clinica_id=".$AuthUser->getUser()->getClinica()->getId().";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                return $this->json($result);
            }
        }else{
            if(empty($AuthUser->getUser()->getClinica())){
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo  FROM `user` as u ,`rol` as r, `persona` as p   WHERE u.emergencia = false AND u.planta = false AND u.persona_id = p.id AND u.usuario_especialidades_id IS NULL AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id  and u.clinica_id=".$request->request->get('clinica').";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                return $this->json($result);
            }else{
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY="SELECT u.id as id ,CONCAT(p.primer_nombre,' ',IFNULL(p.segundo_nombre,' '),' ',p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo FROM `user` as u ,`rol` as r, `persona` as p  WHERE u.emergencia = false AND u.planta = false AND u.persona_id = p.id AND u.usuario_especialidades_id IS NULL AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id  and u.clinica_id=".$AuthUser->getUser()->getClinica()->getId().";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                return $this->json($result);
            }
        }
       

    }

    /**
     * @Route("/cupos", name="ajax_cupos", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cupos(Security $AuthUser, Request $request)
    { 
        //$em = $this->getDoctrine()->getManager();
        $conn = $this->getDoctrine()->getManager()->getConnection();

        $sql= 'CALL calcular_horario_disponible(:idUsuario, :fechaReservacion)';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('idUsuario' => $request->request->get('user') , 'fechaReservacion' => $request->request->get('cita')["fechaReservacion"] ));
        $result= $stmt->fetchAll();
        return $this->json($result);
    }

    
}
