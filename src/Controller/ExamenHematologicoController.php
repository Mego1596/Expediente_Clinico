<?php

namespace App\Controller;

use App\Entity\ExamenHematologico;
use App\Form\ExamenHematologicoType;
use App\Repository\ExamenHematologicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/hematologico")
 */
class ExamenHematologicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_hematologico_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_EXAMENES')")
     */
    public function index(ExamenHematologicoRepository $examenHematologicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }

        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_hematologico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('examen_hematologico/index.html.twig', [
                'examen_hematologicos'          => $result,
                'user'                          => $AuthUser,
                'examen_solicitado'             => $examen_solicitado,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_hematologico_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_EXAMENES')")
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $editar = false;
            $examenHematologico = new ExamenHematologico();
            $form = $this->createForm(ExamenHematologicoType::class, $examenHematologico);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if($form["tipoSerie"]->getData() != ""){
                    if($form["unidad"]->getData() != ""){
                        if($form["valorReferencia"]->getData() != ""){
                            //PROCESAMIENTO DE DATOS
                            $entityManager = $this->getDoctrine()->getManager();
                            $examenHematologico->setExamenSolicitado($examen_solicitado);
                            $entityManager->persist($examenHematologico);
                            $entityManager->flush();
                            $this->addFlash('success', 'Examen añadido con éxito');
                            return $this->redirectToRoute('examen_hematologico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                            //FIN DE PROCESAMIENTO DE DATOS
                        }else{
                            $this->addFlash('fail', 'Error, la serie no puede ir vacío');
                        }
                    }else{
                        $this->addFlash('fail', 'Error, la unidad no puede ir vacío');
                    }
                }else{
                    $this->addFlash('fail', 'Error, el valor de referencia no puede ir vacío, si no hay resultados que asignar por favor asigne " * "');
                }
            }
            return $this->render('examen_hematologico/new.html.twig', [
                'examen_hematologico' => $examenHematologico,
                'examen_solicitado' => $examen_solicitado,
                'editar'            => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_hematologico_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_EXAMENES')")
     */
    public function show(ExamenHematologico $examenHematologico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenHematologico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            return $this->render('examen_hematologico/show.html.twig', [
                'examen_hematologico' => $examenHematologico,
                'examen_solicitado' => $examen_solicitado,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_hematologico_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_EXAMENES')")
     */

    public function edit(Request $request, ExamenHematologico $examenHematologico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenHematologico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        
        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $editar = true;
            $form = $this->createForm(ExamenHematologicoType::class, $examenHematologico);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Examen modificado con éxito');
                return $this->redirectToRoute('examen_hematologico_index', [
                    'id' => $examenHematologico->getId(),
                    'examen_solicitado' => $examen_solicitado->getId(),
                ]);
            }
            return $this->render('examen_hematologico/edit.html.twig', [
                'examen_hematologico' => $examenHematologico,
                'examen_solicitado' => $examen_solicitado,
                'editar'            => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_hematologico_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_EXAMENES')")
     */
    public function delete(Request $request, ExamenHematologico $examenHematologico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenHematologico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$examenHematologico->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenHematologico);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con éxito');
                    return $this->redirectToRoute('examen_hematologico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }

       if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$examenHematologico->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($examenHematologico);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Examen eliminado con éxito');
            return $this->redirectToRoute('examen_hematologico_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }








}
