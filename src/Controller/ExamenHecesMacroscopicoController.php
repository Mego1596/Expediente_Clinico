<?php

namespace App\Controller;

use App\Entity\ExamenHecesMacroscopico;
use App\Entity\ExamenSolicitado;
use App\Form\ExamenHecesMacroscopicoType;
use App\Repository\ExamenHecesMacroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/heces/macroscopico")
 */
class ExamenHecesMacroscopicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_heces_macroscopico_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_EXAMENES')")
     */
    public function index(ExamenHecesMacroscopicoRepository $examenHecesMacroscopicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
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
            $RAW_QUERY = "SELECT examen.* FROM `examen_heces_macroscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('examen_heces_macroscopico/index.html.twig', [
                'examen_heces_macroscopicos'    => $result,
                'cantidad'                      => count($result),
                'user'                          => $AuthUser,
                'examen_solicitado'             => $examen_solicitado,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_heces_macroscopico_new", methods={"GET","POST"})
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
            $examenHecesMacroscopico = new ExamenHecesMacroscopico();
            $form = $this->createForm(ExamenHecesMacroscopicoType::class, $examenHecesMacroscopico);
            $form->handleRequest($request);

            //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_heces_macroscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if(count($result) < 1){
                if ($form->isSubmitted() && $form->isValid()) {
                    if($form["aspecto"]->getData() != ""){
                        if($form["consistencia"]->getData() != ""){
                            if($form["color"]->getData() != ""){
                                if($form["olor"]->getData() != ""){
                                    //PROCESAMIENTO DE DATOS
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $examenHecesMacroscopico->setExamenSolicitado($examen_solicitado);
                                    $entityManager->persist($examenHecesMacroscopico);
                                    $entityManager->flush();
                                    $this->addFlash('success', 'Examen añadido con éxito');
                                    return $this->redirectToRoute('examen_heces_macroscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                                    //FIN DE PROCESAMIENTO DE DATOS

                                }else{
                                    $this->addFlash('fail', 'Error, aspecto no puede ir vacío');
                                }
                            }else{
                                $this->addFlash('fail', 'Error, consistencia no puede ir vacío');
                            }
                        }else{
                            $this->addFlash('fail', 'Error, color no puede ir vacío');
                        }
                    }else{
                        $this->addFlash('fail', 'Error, olor no puede ir vacío');
                    }
                }
            }else{
                $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o eliminelo si desea crear uno nuevo.');
                return $this->redirectToRoute('examen_heces_macroscopico_index', ['examen_solicitado' => $examen_solicitado->getId()]);
            }

            return $this->render('examen_heces_macroscopico/new.html.twig', [
                'examen_heces_macroscopico' => $examenHecesMacroscopico,
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
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_macroscopico_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_EXAMENES')")
     */
    public function show(ExamenHecesMacroscopico $examenHecesMacroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    return $this->render('examen_heces_macroscopico/show.html.twig', [
                        'examen_heces_macroscopico' => $examenHecesMacroscopico,
                        'examen_solicitado' => $examen_solicitado,
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        
        return $this->render('examen_heces_macroscopico/show.html.twig', [
            'examen_heces_macroscopico' => $examenHecesMacroscopico,
            'examen_solicitado' => $examen_solicitado,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_heces_macroscopico_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_EXAMENES')")
     */
    public function edit(Request $request, ExamenHecesMacroscopico $examenHecesMacroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    { 
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(ExamenHecesMacroscopicoType::class, $examenHecesMacroscopico);
                    $form->handleRequest($request);
                    
                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Examen modificado con éxito');
                        return $this->redirectToRoute('examen_heces_macroscopico_index', [
                            'id' => $examenHecesMacroscopico->getId(),
                            'examen_solicitado' => $examen_solicitado->getId(),
                        ]);
                    }
        
                    return $this->render('examen_heces_macroscopico/edit.html.twig', [
                        'examen_heces_macroscopico' => $examenHecesMacroscopico,
                        'examen_solicitado' => $examen_solicitado,
                        'editar'            => $editar,
                        'form' => $form->createView(),
                    ]);
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
            $editar = true;
            $form = $this->createForm(ExamenHecesMacroscopicoType::class, $examenHecesMacroscopico);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Examen modificado con éxito');
                return $this->redirectToRoute('examen_heces_macroscopico_index', [
                    'id' => $examenHecesMacroscopico->getId(),
                    'examen_solicitado' => $examen_solicitado->getId(),
                ]);
            }
            return $this->render('examen_heces_macroscopico/edit.html.twig', [
                'examen_heces_macroscopico' => $examenHecesMacroscopico,
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
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_macroscopico_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_EXAMENES')")
     */
    public function delete(Request $request, ExamenHecesMacroscopico $examenHecesMacroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$examenHecesMacroscopico->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenHecesMacroscopico);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con éxito');
                    return $this->redirectToRoute('examen_heces_macroscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
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
            if ($this->isCsrfTokenValid('delete'.$examenHecesMacroscopico->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($examenHecesMacroscopico);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Examen eliminado con éxito');
            return $this->redirectToRoute('examen_heces_macroscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
