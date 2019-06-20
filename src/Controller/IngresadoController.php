<?php

namespace App\Controller;

use App\Entity\Ingresado;
use App\Entity\Expediente;
use App\Entity\Habitacion;
use App\Entity\Sala;
use App\Entity\Clinica;
use App\Entity\User;
use App\Entity\Camilla;
use App\Form\IngresadoType;
use App\Repository\IngresadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/ingresado")
 */
class IngresadoController extends AbstractController
{
    /**
     * @Route("/", name="ingresado_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_INGRESADO')")
     */
    public function index(IngresadoRepository $ingresadoRepository, Security $AuthUser): Response
    {

        //UBICAR SOLO LOS DE MI CLINICA
        if(empty($AuthUser->getUser()->getClinica())){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY="SELECT i.id as id,
            i.fecha_ingreso as fechaIngreso, CONCAT(p.primer_nombre,' ' ,IFNULL(p.segundo_nombre,' '),' ' ,p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo, s.nombre_sala as sala, c.nombre_clinica as nombre_clinica
            FROM `ingresado` as i,`expediente` as exp, `camilla` as cam, `habitacion` as h, `sala` as s, `clinica` as c, `user` as u,`user` as u2, `persona` as p WHERE
            i.expediente_id    =exp.id          AND
            exp.usuario_id     =u.id            AND
            i.camilla_id       =cam.id          AND
            cam.habitacion_id  =h.id            AND
            h.sala_id          =s.id            AND
            s.clinica_id       =c.id            AND
            u.persona_id       =p.id            AND
            i.fecha_salida IS NULL              AND
            u2.id              =i.usuario_id";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
        }else{
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY="SELECT i.id as id,
            i.fecha_ingreso as fechaIngreso, CONCAT(p.primer_nombre,' ' ,IFNULL(p.segundo_nombre,' '),' ' ,p.primer_apellido,' ',IFNULL(p.segundo_apellido,' ')) as nombre_completo, s.nombre_sala as sala, c.nombre_clinica as nombre_clinica
            FROM `ingresado` as i,`expediente` as exp, `camilla` as cam, `habitacion` as h, `sala` as s, `clinica` as c, `user` as u,`user` as u2, `persona` as p WHERE
            i.expediente_id    =exp.id          AND
            exp.usuario_id     =u.id            AND
            u.clinica_id       =c.id            AND
            i.camilla_id       =cam.id          AND
            cam.habitacion_id  =h.id            AND
            h.sala_id          =s.id            AND
            s.clinica_id       =c.id            AND
            u2.id              =i.usuario_id    AND
            u.persona_id       =p.id            AND
            u2.clinica_id      =c.id            AND
            i.fecha_salida IS NULL              AND
            c.id = ".$AuthUser->getUser()->getClinica()->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
        }
        
        return $this->render('ingresado/index.html.twig', [
            'ingresados' => $result,
            'user'       => $AuthUser,
        ]);
    }

    /**
     * @Route("/new/{expediente}", name="ingresado_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_INGRESADO')")
     */
    public function new(Request $request, Expediente $expediente, Security $AuthUser): Response
    {   
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() ){

            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('ingresado_index');
            }
        }
        if($request->request->get('user') != ""){
            if($request->request->get('camilla') != ""){
                $clinicaDoctor     = $this->getDoctrine()->getRepository(User::class)->find($request->request->get('user'));
                $clinicaCamilla    = $this->getDoctrine()->getRepository(Camilla::class)->find($request->request->get('camilla'));
                $clinicaExpediente = $this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()); 
                if($clinicaDoctor->getClinica()->getId() == $clinicaCamilla->getHabitacion()->getSala()->getClinica()->getId() && $clinicaDoctor->getClinica()->getId() == $clinicaExpediente->getUsuario()->getClinica()->getId()){
                    date_default_timezone_set("America/El_Salvador");
                    $ingresado = new Ingresado();
                    if ($this->isCsrfTokenValid('create-item', $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $ingresado->setCamilla($this->getDoctrine()->getRepository(Camilla::class)->find($request->request->get('camilla')));
                        $ingresado->setExpediente($expediente);
                        $ingresado->setUsuario($this->getDoctrine()->getRepository(User::class)->find($request->request->get('user')));
                        $ingresado->setFechaIngreso(date_create());
                        $entityManager->persist($ingresado);
                        $entityManager->flush();
                        $this->addFlash('success', 'Paciente ingresado con éxito');
                        return $this->redirectToRoute('ingresado_index');    
                    }else{
                        return $this->redirectToRoute('expediente_show',['id' => $expediente->getId()]);  
                    }
                }else{
                    $this->addFlash('fail', 'Error, los datos no coinciden con la clínica');
                    return $this->redirectToRoute('expediente_show',['id' => $expediente->getId()]);  
                }
            }else{
                $this->addFlash('fail', 'Error, no se seleccionó ninguna camilla');
                return $this->redirectToRoute('expediente_show',['id' => $expediente->getId()]);  
            }
        }else{
            $this->addFlash('fail', 'Error, no se seleccionó ningún doctor');
            return $this->redirectToRoute('expediente_show',['id' => $expediente->getId()]);  
        }       
    }

    /**
     * @Route("/{id}/", name="ingresado_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_INGRESADO')")
     */
    public function show(IngresadoRepository $ingresadoRepository, Ingresado $ingresado, Security $AuthUser): Response
    {   
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $ingresado->getExpediente()->getUsuario()->getClinica()->getId() ){

            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('ingresado_index');
            }
        }
        
        if($ingresado->getExpediente()->getHabilitado()){
                //UBICAR SOLO LOS DE MI CLINICA
            if(empty($AuthUser->getUser()->getClinica())){
                
            $conn = $this->getDoctrine()->getManager()->getConnection();
            $sql = '
                SELECT i.id as id, i.creado_en as creadoEn, i.actualizado_en as actualizadoEn,
                i.fecha_ingreso as fechaIngreso, i.fecha_salida as fechaSalida, exp.numero_expediente as expediente, exp.id as idExpediente,
                u2.emergencia as emergencia, u2.planta as planta, CONCAT(p.primer_nombre," " ,IFNULL(p.segundo_nombre," ")," " ,p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo, CONCAT(p2.primer_nombre," " ,IFNULL(p2.segundo_nombre," ")," " ,p2.primer_apellido," ",IFNULL(p2.segundo_apellido," ")) as nombre_completoD, s.nombre_sala as sala, c.nombre_clinica as nombr_clinica,
                h.numero_habitacion as habitacion, cam.numero_camilla as camilla
                FROM `ingresado` as i,`expediente` as exp, `camilla` as cam, `habitacion` as h, `sala` as s, `clinica` as c, `user` as u,`user` as u2, `persona` as p, `persona` as p2 WHERE
                i.expediente_id    =exp.id          AND
                exp.usuario_id     =u.id            AND
                i.camilla_id       =cam.id          AND
                cam.habitacion_id  =h.id            AND
                h.sala_id          =s.id            AND
                s.clinica_id       =c.id            AND
                u.persona_id       =p.id            AND
                i.fecha_salida IS NULL              AND
                u2.id              =i.usuario_id    AND
                u2.id              =p2.id           AND
                i.id = :idIngregado
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('idIngregado' => $ingresado->getId()));
            $result= $stmt->fetch();
            

            }else{
                
                   
            $conn = $this->getDoctrine()->getManager()->getConnection();
            $sql = '
                SELECT i.id as id, i.creado_en as creadoEn, i.actualizado_en as actualizadoEn,
                i.fecha_ingreso as fechaIngreso, i.fecha_salida as fechaSalida, exp.numero_expediente as expediente, exp.id as idExpediente,
                u2.emergencia as emergencia, u2.planta as planta, CONCAT(p.primer_nombre," " ,IFNULL(p.segundo_nombre," ")," " ,p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo, CONCAT(p2.primer_nombre," " ,IFNULL(p2.segundo_nombre," ")," " ,p2.primer_apellido," ",IFNULL(p2.segundo_apellido," ")) as nombre_completoD, s.nombre_sala as sala, c.nombre_clinica as nombr_clinica,
                h.numero_habitacion as habitacion, cam.numero_camilla as camilla
                FROM `ingresado` as i,`expediente` as exp, `camilla` as cam, `habitacion` as h, `sala` as s, `clinica` as c, `user` as u,`user` as u2, `persona` as p, `persona` as p2 WHERE
                i.expediente_id    =exp.id          AND
                exp.usuario_id     =u.id            AND
                i.camilla_id       =cam.id          AND
                cam.habitacion_id  =h.id            AND
                h.sala_id          =s.id            AND
                s.clinica_id       =c.id            AND
                u.persona_id       =p.id            AND
                i.fecha_salida IS NULL              AND
                u2.id              =i.usuario_id    AND
                u2.id              =p2.id           AND
                i.id = :idIngregado                 AND
                c.id = :ClinicaUsuario
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('idIngregado' => $ingresado->getId(), 'ClinicaUsuario' => $AuthUser->getUser()->getClinica()->getId()));
            $result= $stmt->fetch();
            

            }
            return $this->render('ingresado/show.html.twig', [
                'ingresado' => $result,
            ]);
        }else{
            $this->addFlash('fail', 'Lo sentimos mucho, no se puede acceder a esta información por que el paciente no está habilitado');
            return $this->redirectToRoute('ingresado_index');
        }
    }

    /**
     * @Route("/{id}/edit", name="ingresado_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_INGRESADO')")
     */
    public function darDeAlta(Request $request, Ingresado $ingresado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $ingresado->getExpediente()->getUsuario()->getClinica()->getId() ){

            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('ingresado_index');
            }
        }
        
        if($ingresado->getFechaSalida() == null){
            if($ingresado->getExpediente()->getHabilitado()){
                date_default_timezone_set("America/El_Salvador");
                if ($this->isCsrfTokenValid('update-item', $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($ingresado);
                    $entityManager->flush();
                    $this->addFlash('success', 'Paciente dado de alta con éxito');
                    return $this->redirectToRoute('ingresado_index'); 
                }else{
                    $this->addFlash('fail', 'Ups, algo ha salido mal inténtelo de nuevo');
                    return $this->redirectToRoute('ingresado_index'); 
                }
            }else{
                $this->addFlash('fail', 'Lo sentimos mucho, no se puede acceder a esta información por que el paciente no está habilitado');
                return $this->redirectToRoute('ingresado_index'); 
            }
        }else{
            $this->addFlash('fail', 'Error, este paciente ya se le ha dado el alta');
            return $this->redirectToRoute('ingresado_index');    
        }

    }

    /**
     * @Route("/{id}", name="ingresado_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_INGRESADO')")
     */
    public function delete(Request $request, Ingresado $ingresado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $ingresado->getExpediente()->getUsuario()->getClinica()->getId() ){

            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('ingresado_index');
            }
        }

        if($ingresado->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$ingresado->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($ingresado);
                $entityManager->flush();
            }
            return $this->redirectToRoute('ingresado_index');
        }else{ 
            $this->addFlash('fail', 'Lo sentimos mucho, no se puede acceder a esta información por que el paciente no está habilitado');
            return $this->redirectToRoute('ingresado_index');   
        }
    }
}
