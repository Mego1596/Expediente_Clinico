<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     */
    public function home(Security $AuthUser)
    {   
        return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
        'user'            => $AuthUser,
        ]);
    }
    /**
     * @Route("/cambioContrasena", name="app_cambio")
     * @Security2("is_authenticated()")
     */
    public function cambio_contrasena(Security $AuthUser)
    {   
        return $this->render('user/passwordUpdate.html.twig', [
        'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/medicoGeneral", name="ajax_cargaGeneral", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaGeneral(Security $AuthUser, Request $request)
    { 
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY="SELECT u.id as id ,CONCAT('Dr. ',u.nombres,' ',u.apellidos) as nombre_completo FROM `user` as u ,`rol` as r  WHERE u.usuario_especialidades_id IS NULL AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id;";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->json($result);

    }

    /**
     * @Route("/medicoEspecialidad", name="ajax_cargaEspecialidad", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cargaEspecialidad(Security $AuthUser, Request $request)
    { 
        if($request->request->get('especialidad')!=""){
             $em = $this->getDoctrine()->getManager();
            $RAW_QUERY="SELECT u.id as id ,CONCAT('Dr. ',u.nombres,' ',u.apellidos) as nombre_completo FROM `user` as u ,`rol` as r  WHERE u.usuario_especialidades_id =".$request->request->get('especialidad')." AND r.nombre_rol='ROLE_DOCTOR' AND u.rol_id = r.id;";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->json($result);
        }else{
            return $this->redirectToRoute('ajax_cargaGeneral');
        }
       

    }

    /**
     * @Route("/cupos", name="ajax_cupos", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     */
    public function cupos(Security $AuthUser, Request $request)
    { 
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY="SELECT r2.id as id , r2.intervalo as intervalo,r2.cantidad as cantidad,
                        (CASE
                            WHEN r2.cantidad >= 1 THEN 'No'
                            ELSE 'Si'
                        END) as disponibilidad FROM 
                        (SELECT r.id, r.intervalo, sum(r.cantidad) as cantidad FROM (
                            SELECT 0 as id,'00:00:00-00:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 0 as id,'00:30:00-01:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT 1 as id,'01:00:00-01:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 1 as id,'01:30:00-02:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT 2 as id,'02:00:00-02:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 2 as id,'02:30:00-03:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 3 as id,'03:00:00-03:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 3 as id,'03:30:00-04:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 4 as id,'04:00:00-04:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 4 as id,'04:30:00-05:00:00' AS intervalo, 0 as cantidad UNION
                            
                            SELECT 5 as id,'05:00:00-05:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 5 as id,'05:30:00-06:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT 6 as id,'06:00:00-06:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 6 as id,'06:30:00-07:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 7 as id,'07:00:00-07:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 7 as id,'07:30:00-08:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 8 as id,'08:00:00-08:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 8 as id,'08:30:00-09:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 9 as id,'09:00:00-09:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 9 as id,'09:30:00-10:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 10 as id,'10:00:00-10:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 10 as id,'10:30:00-11:00:00' AS intervalo, 0 as cantidad UNION 
                              
                            SELECT 11 as id,'11:00:00-11:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 11 as id,'11:30:00-12:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT 12 as id,'12:00:00-12:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 12 as id,'12:30:00-13:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT 13 as id,'13:00:00-13:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 13 as id,'13:30:00-14:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 14 as id,'14:00:00-14:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 14 as id,'14:30:00-15:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 15 as id,'15:00:00-15:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 15 as id,'15:30:00-16:00:00' AS intervalo, 0 as cantidad UNION
                            
                            SELECT 16 as id,'16:00:00-16:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 16 as id,'16:30:00-17:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT 17 as id,'17:00:00-17:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 17 as id,'17:30:00-18:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 18 as id,'18:00:00-18:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 18 as id,'18:30:00-19:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 19 as id,'19:00:00-19:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 19 as id,'19:30:00-20:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 20 as id,'20:00:00-20:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 20 as id,'20:30:00-21:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 21 as id,'21:00:00-21:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 21 as id,'21:30:00-22:00:00' AS intervalo, 0 as cantidad UNION
                              
                            SELECT 22 as id,'22:00:00-22:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 22 as id,'22:30:00-23:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT 23 as id,'23:00:00-23:30:00' AS intervalo, 0 as cantidad UNION
                            SELECT 23 as id,'23:30:00-00:00:00' AS intervalo, 0 as cantidad UNION
                             
                            SELECT HOUR(fecha_reservacion) as id ,
                                (CASE
                                    WHEN TIME(fecha_reservacion) BETWEEN '00:00:00' AND '00:29:00' THEN '00:00:00-00:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '00:30:00' AND '00:59:00' THEN '00:30:00-01:00:00'
                                 
                                    WHEN TIME(fecha_reservacion) BETWEEN '01:00:00' AND '01:29:00' THEN '01:00:00-01:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '01:30:00' AND '01:59:00' THEN '01:30:00-02:00:00'
                                 
                                    WHEN TIME(fecha_reservacion) BETWEEN '02:00:00' AND '02:29:00' THEN '02:00:00-02:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '02:30:00' AND '02:59:00' THEN '02:30:00-03:00:00'
                                  
                                    WHEN TIME(fecha_reservacion) BETWEEN '03:00:00' AND '03:29:00' THEN '03:00:00-03:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '03:30:00' AND '03:59:00' THEN '03:30:00-04:00:00'
                                  
                                    WHEN TIME(fecha_reservacion) BETWEEN '04:00:00' AND '04:29:00' THEN '04:00:00-04:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '04:30:00' AND '04:59:00' THEN '04:30:00-05:00:00'
                                
                                    WHEN TIME(fecha_reservacion) BETWEEN '05:00:00' AND '05:29:00' THEN '05:00:00-05:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '05:30:00' AND '05:59:00' THEN '05:30:00-06:00:00'
                                 
                                    WHEN TIME(fecha_reservacion) BETWEEN '06:00:00' AND '06:29:00' THEN '06:00:00-06:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '06:30:00' AND '06:59:00' THEN '06:30:00-07:00:00'
                              
                                    WHEN TIME(fecha_reservacion) BETWEEN '07:00:00' AND '07:29:00' THEN '07:00:00-07:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '07:30:00' AND '07:59:00' THEN '07:30:00-08:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '08:00:00' AND '08:29:00' THEN '08:00:00-08:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '08:30:00' AND '08:59:00' THEN '08:30:00-09:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '09:00:00' AND '09:29:00' THEN '09:00:00-09:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '09:30:00' AND '09:59:00' THEN '09:30:00-10:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '10:00:00' AND '10:29:00' THEN '10:00:00-10:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '10:30:00' AND '10:59:00' THEN '10:30:00-11:00:00' 
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '11:00:00' AND '11:29:00' THEN '11:00:00-11:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '00:30:00' AND '11:59:00' THEN '11:30:00-12:00:00'
                     
                                    WHEN TIME(fecha_reservacion) BETWEEN '12:00:00' AND '12:29:00' THEN '12:00:00-12:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '12:30:00' AND '12:59:00' THEN '12:30:00-13:00:00'
                     
                                    WHEN TIME(fecha_reservacion) BETWEEN '13:00:00' AND '13:29:00' THEN '13:00:00-13:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '13:30:00' AND '13:59:00' THEN '13:30:00-14:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '14:00:00' AND '14:29:00' THEN '14:00:00-14:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '14:30:00' AND '14:59:00' THEN '14:30:00-15:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '15:00:00' AND '15:29:00' THEN '15:00:00-15:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '15:30:00' AND '15:59:00' THEN '15:30:00-16:00:00'
                    
                                    WHEN TIME(fecha_reservacion) BETWEEN '16:00:00' AND '16:29:00' THEN '16:00:00-16:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '16:30:00' AND '16:59:00' THEN '16:30:00-17:00:00'
                     
                                    WHEN TIME(fecha_reservacion) BETWEEN '17:00:00' AND '17:29:00' THEN '17:00:00-17:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '17:30:00' AND '17:59:00' THEN '17:30:00-18:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '18:00:00' AND '18:29:00' THEN '18:00:00-18:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '18:30:00' AND '18:59:00' THEN '18:30:00-19:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '19:00:00' AND '19:29:00' THEN '19:00:00-19:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '19:30:00' AND '19:59:00' THEN '19:30:00-20:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '20:00:00' AND '20:29:00' THEN '20:00:00-20:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '20:30:00' AND '20:59:00' THEN '20:30:00-21:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '21:00:00' AND '21:29:00' THEN '21:00:00-21:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '21:30:00' AND '21:59:00' THEN '21:30:00-22:00:00'
                      
                                    WHEN TIME(fecha_reservacion) BETWEEN '22:00:00' AND '22:29:00' THEN '22:00:00-22:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '22:30:00' AND '22:59:00' THEN '22:30:00-23:00:00'
                     
                                    WHEN TIME(fecha_reservacion) BETWEEN '23:00:00' AND '23:29:00' THEN '23:00:00-23:30:00'
                                    WHEN TIME(fecha_reservacion) BETWEEN '23:30:00' AND '23:59:00' THEN '23:30:00-00:00:00'
                                END) 
                            as intervalo, count(*) FROM `cita` WHERE usuario_id=".$request->request->get('user')
                            ." AND fecha_reservacion BETWEEN '".$request->request->get('cita')["fechaReservacion"]." 00:00:00' AND '"
                            .$request->request->get('cita')["fechaReservacion"]." 23:59:59' GROUP BY intervalo) as r GROUP BY r.intervalo) 
                            as r2 GROUP BY r2.intervalo;";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->json($result);
    }

    
}
