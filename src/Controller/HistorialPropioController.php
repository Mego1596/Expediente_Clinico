<?php

namespace App\Controller;

use App\Entity\HistorialPropio;
use App\Entity\Expediente;
use App\Form\HistorialPropioType;
use App\Repository\HistorialPropioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/historial/propio")
 */
class HistorialPropioController extends AbstractController
{
    /**
     * @Route("/{expediente}", name="historial_propio_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_HISTORIAL_PROPIO')")
     */
    public function index(HistorialPropioRepository $historialPropioRepository, Expediente $expediente,Security $AuthUser): Response
    {
        //OBTENCION DEL NOMBRE DEL PACIENTE
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = '
            SELECT CONCAT(p.primer_nombre," " ,IFNULL(p.segundo_nombre," ")," " ,p.primer_apellido," ",IFNULL(p.segundo_apellido," ")) as nombre_completo
            FROM expediente as exp, user as u, persona as p 
            WHERE exp.id   = :idExpediente AND
            exp.usuario_id = u.id          AND
            u.persona_id   = p.id 
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('idExpediente' => $expediente->getId()));
        $nombre= $stmt->fetch();

        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
               if($expediente->getHabilitado()){

                    
                    //OBTENCION DE LAS HISTORIAS PROPIAS DEL PACIENTE    
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT historial.* FROM historial_propio as historial, expediente as exp WHERE exp.id=historial.expediente_id AND expediente_id=".$expediente->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll(); 


                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
                return $this->render('historial_propio/index.html.twig', [
                    'historial_propios' => $result,
                    'user'              => $AuthUser,
                    'expediente'        => $expediente,
                    'nombre'            => $nombre,
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT historial.* FROM historial_propio as historial, expediente as exp WHERE exp.id=historial.expediente_id AND expediente_id=".$expediente->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();           
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        return $this->render('historial_propio/index.html.twig', [
            'historial_propios' => $result,
            'user'              => $AuthUser,
            'expediente'        => $expediente,
            'nombre'            => $nombre,
        ]);
    }

    /**
     * @Route("/new/{expediente}", name="historial_propio_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_HISTORIAL_PROPIO')")
     */
    public function new(Request $request, Expediente $expediente, Security $AuthUser): Response
    {

        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    $editar = false;
                    $historialPropio = new HistorialPropio();
                    $form = $this->createForm(HistorialPropioType::class, $historialPropio);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
                        if($form["descripcion"]->getData() != ""){
                            $historialPropio->setDescripcion($form["descripcion"]->getData());
                            $historialPropio->setExpediente($expediente);
                            $entityManager->persist($historialPropio);
                            $entityManager->flush();
                        }else{
                            $this->addFlash('fail', 'Error, el campo de la descripción no puede ir vacía');
                            return $this->render('historial_propio/new.html.twig', [
                                'historial_propio' => $historialPropio,
                                'expediente' => $expediente,
                                'editar'     => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                        $this->addFlash('success', 'Historial añadido con éxito');
                        return $this->redirectToRoute('historial_propio_index',['expediente' => $expediente->getId()]);
                    }

                    return $this->render('historial_propio/new.html.twig', [
                        'historial_propio' => $historialPropio,
                        'expediente' => $expediente,
                        'editar'     => $editar,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            $editar = false;
            $historialPropio = new HistorialPropio();
            $form = $this->createForm(HistorialPropioType::class, $historialPropio);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                if($form["descripcion"]->getData() != ""){
                    $historialPropio->setDescripcion($form["descripcion"]->getData());
                    $historialPropio->setExpediente($expediente);
                    $entityManager->persist($historialPropio);
                    $entityManager->flush();
                }else{
                    $this->addFlash('fail', 'Error, el campo de la descripción no puede ir vacía');
                    return $this->render('historial_propio/new.html.twig', [
                        'historial_propio' => $historialPropio,
                        'expediente' => $expediente,
                        'editar'     => $editar,
                        'form' => $form->createView(),
                    ]);
                }
                $this->addFlash('success', 'Historial añadido con éxito');
                return $this->redirectToRoute('historial_propio_index',['expediente' => $expediente->getId()]);
            }
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }

        return $this->render('historial_propio/new.html.twig', [
            'historial_propio' => $historialPropio,
            'expediente' => $expediente,
            'editar'     => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{expediente}", name="historial_propio_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_HISTORIAL_PROPIO')")
     */
    public function show(HistorialPropio $historialPropio, Expediente $expediente, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $historialPropio->getExpediente()->getUsuario()->getClinica()->getId() && $historialPropio->getExpediente()->getId() == $expediente->getId() ){
                if($expediente->getHabilitado()){
                    return $this->render('historial_propio/show.html.twig', [
                    'historial_propio' => $historialPropio,
                    'expediente'       => $expediente,
                ]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            return $this->render('historial_propio/show.html.twig', [
                'historial_propio' => $historialPropio,
                'expediente'       => $expediente,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{id}/{expediente}/edit", name="historial_propio_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_HISTORIAL_PROPIO')")
     */
    public function edit(Request $request, HistorialPropio $historialPropio, Expediente $expediente, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $historialPropio->getExpediente()->getUsuario()->getClinica()->getId() && $historialPropio->getExpediente()->getId() == $expediente->getId()  ){
                if($expediente->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(HistorialPropioType::class, $historialPropio);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Historial modificado con éxito');
                        return $this->redirectToRoute('historial_propio_index',['expediente' => $expediente->getId()]);
                    }

                    return $this->render('historial_propio/edit.html.twig', [
                        'historial_propio' => $historialPropio,
                        'expediente'       => $expediente,
                        'editar'     => $editar,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            $editar = true;
            $form = $this->createForm(HistorialPropioType::class, $historialPropio);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Historial modificado con éxito');
                return $this->redirectToRoute('historial_propio_index',['expediente' => $expediente->getId()]);
            }

            return $this->render('historial_propio/edit.html.twig', [
                'historial_propio' => $historialPropio,
                'expediente'       => $expediente,
                'editar'     => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{id}/{expediente}", name="historial_propio_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_HISTORIAL_PROPIO')")
     */
    public function delete(Request $request, HistorialPropio $historialPropio, Expediente $expediente, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $historialPropio->getExpediente()->getUsuario()->getClinica()->getId() && $historialPropio->getExpediente()->getId() == $expediente->getId()  ){
                if($expediente->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$historialPropio->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($historialPropio);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Historial eliminado con éxito');
                    return $this->redirectToRoute('historial_propio_index',['expediente' => $expediente->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if ($this->isCsrfTokenValid('delete'.$historialPropio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($historialPropio);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Historial eliminado con éxito');
        return $this->redirectToRoute('historial_propio_index',['expediente' => $expediente->getId()]);
    }
}
