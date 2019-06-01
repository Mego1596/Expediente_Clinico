<?php

namespace App\Controller;

use App\Entity\ExamenOrinaCristaluria;
use App\Form\ExamenOrinaCristaluriaType;
use App\Repository\ExamenOrinaCristaluriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/orina/cristaluria")
 */
class ExamenOrinaCristaluriaController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_orina_cristaluria_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_EXAMENES')")
     */

    public function index(examenOrinaCristaluriaRepository $examenOrinaCristaluriaRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){

                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_orina_cristaluria` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    return $this->render('examen_orina_cristaluria/index.html.twig', [
                        'examen_orina_cristalurias'    => $result,
                        'cantidad'                      => count($result),
                        'user'                          => $AuthUser,
                        'examen_solicitado'             => $examen_solicitado,
                    ]);

                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }

        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_orina_cristaluria` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('examen_orina_cristaluria/index.html.twig', [
                'examen_orina_cristalurias'    => $result,
                'cantidad'                      => count($result),
                'user'                          => $AuthUser,
                'examen_solicitado'             => $examen_solicitado,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_orina_cristaluria_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_EXAMENES')")
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = false;
                    $examenOrinaCristaluria = new examenOrinaCristaluria();
                    $form = $this->createForm(examenOrinaCristaluriaType::class, $examenOrinaCristaluria);
                    $form->handleRequest($request);

                    //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_orina_cristaluria` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if(count($result) < 1){
                        if ($form->isSubmitted() && $form->isValid()) {
                            if($form["uratosAmorfos"]->getData() != ""){
                                if($form["acidoUrico"]->getData() != ""){
                                    if($form["oxalatosCalcicos"]->getData() != ""){
                                        if($form["fosfatosAmorfos"]->getData() != ""){
                                            if($form["fosfatosCalcicos"]->getData() != ""){
                                                if($form["fosfatosAmonicos"]->getData() != ""){
                                                    if($form["riesgoLitogenico"]->getData() != ""){
                                                        //PROCESAMIENTO DE DATOS
                                                        $entityManager = $this->getDoctrine()->getManager();
                                                        $examenOrinaCristaluria->setExamenSolicitado($examen_solicitado);
                                                        $entityManager->persist($examenOrinaCristaluria);
                                                        $entityManager->flush();
                                                        //FIN DE PROCESAMIENTO DE DATOS
                                                    }else{
                                                        $this->addFlash('fail', 'Error, el riesgo litogenico no puede ir vacio');
                                                        return $this->render('examen_orina_cristaluria/new.html.twig', [
                                                            'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                                            'examen_solicitado' => $examen_solicitado,
                                                            'editar'            => $editar,
                                                            'form' => $form->createView(),
                                                        ]);
                                                    }
                                                }else{
                                                    $this->addFlash('fail', 'Error, los fosfatos amonicos no pueden ir vacios');
                                                    return $this->render('examen_orina_cristaluria/new.html.twig', [
                                                        'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                                        'examen_solicitado' => $examen_solicitado,
                                                        'editar'            => $editar,
                                                        'form' => $form->createView(),
                                                    ]);
                                                }
                                            }else{
                                                $this->addFlash('fail', 'Error, los fosfatos calcicos no pueden ir vacios');
                                                return $this->render('examen_orina_cristaluria/new.html.twig', [
                                                    'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                                    'examen_solicitado' => $examen_solicitado,
                                                    'editar'            => $editar,
                                                    'form' => $form->createView(),
                                                ]);
                                            }
                                        }else{
                                            $this->addFlash('fail', 'Error, los fosfatos amorfos no pueden ir vacios');
                                            return $this->render('examen_orina_cristaluria/new.html.twig', [
                                                'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, los oxalatos calcicos no pueden ir vacios');
                                        return $this->render('examen_orina_cristaluria/new.html.twig', [
                                            'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, el acido urico no puede ir vacio');
                                    return $this->render('examen_orina_cristaluria/new.html.twig', [
                                        'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, los uratos amorfos no pueden ir vacios');
                                return $this->render('examen_orina_cristaluria/new.html.twig', [
                                    'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                            $this->addFlash('success', 'Examen añadido con exito');
                            return $this->redirectToRoute('examen_orina_cristaluria_index',['examen_solicitado' => $examen_solicitado->getId()]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o eliminelo si desea crear uno nuevo.');
                        return $this->redirectToRoute('examen_orina_cristaluria_index', ['examen_solicitado' => $examen_solicitado->getId()]);
                    }

                    return $this->render('examen_orina_cristaluria/new.html.twig', [
                        'examen_orina_cristaluria' => $examenOrinaCristaluria,
                        'examen_solicitado' => $examen_solicitado,
                        'editar'            => $editar,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }

        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $editar = false;
            $examenOrinaCristaluria = new examenOrinaCristaluria();
            $form = $this->createForm(examenOrinaCristaluriaType::class, $examenOrinaCristaluria);
            $form->handleRequest($request);

            //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_orina_cristaluria` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if(count($result) < 1){
                if ($form->isSubmitted() && $form->isValid()) {
                    if($form["uratosAmorfos"]->getData() != ""){
                        if($form["acidoUrico"]->getData() != ""){
                            if($form["oxalatosCalcicos"]->getData() != ""){
                                if($form["fosfatosAmorfos"]->getData() != ""){
                                    if($form["fosfatosCalcicos"]->getData() != ""){
                                        if($form["fosfatosAmonicos"]->getData() != ""){
                                            if($form["riesgoLitogenico"]->getData() != ""){
                                                //PROCESAMIENTO DE DATOS
                                                $entityManager = $this->getDoctrine()->getManager();
                                                $examenOrinaCristaluria->setExamenSolicitado($examen_solicitado);
                                                $entityManager->persist($examenOrinaCristaluria);
                                                $entityManager->flush();
                                                //FIN DE PROCESAMIENTO DE DATOS
                                            }else{
                                                $this->addFlash('fail', 'Error, el riesgo litogenico no puede ir vacio');
                                                return $this->render('examen_orina_cristaluria/new.html.twig', [
                                                    'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                                    'examen_solicitado' => $examen_solicitado,
                                                    'editar'            => $editar,
                                                    'form' => $form->createView(),
                                                ]);
                                            }
                                        }else{
                                            $this->addFlash('fail', 'Error, los fosfatos amonicos no pueden ir vacios');
                                            return $this->render('examen_orina_cristaluria/new.html.twig', [
                                                'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, los fosfatos calcicos no pueden ir vacios');
                                        return $this->render('examen_orina_cristaluria/new.html.twig', [
                                            'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, los fosfatos amorfos no pueden ir vacios');
                                    return $this->render('examen_orina_cristaluria/new.html.twig', [
                                        'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, los oxalatos calcicos no pueden ir vacios');
                                return $this->render('examen_orina_cristaluria/new.html.twig', [
                                    'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, el acido urico no puede ir vacio');
                            return $this->render('examen_orina_cristaluria/new.html.twig', [
                                'examen_orina_cristaluria' => $examenOrinaCristaluria,
                                'examen_solicitado' => $examen_solicitado,
                                'editar'            => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, los uratos amorfos no pueden ir vacios');
                        return $this->render('examen_orina_cristaluria/new.html.twig', [
                            'examen_orina_cristaluria' => $examenOrinaCristaluria,
                            'examen_solicitado' => $examen_solicitado,
                            'editar'            => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                    $this->addFlash('success', 'Examen añadido con exito');
                    return $this->redirectToRoute('examen_orina_cristaluria_index',['examen_solicitado' => $examen_solicitado->getId()]);
                }
            }else{
                $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o eliminelo si desea crear uno nuevo.');
                return $this->redirectToRoute('examen_orina_cristaluria_index', ['examen_solicitado' => $examen_solicitado->getId()]);
            }

            return $this->render('examen_orina_cristaluria/new.html.twig', [
                'examen_orina_cristaluria' => $examenOrinaCristaluria,
                'examen_solicitado' => $examen_solicitado,
                'editar'            => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_cristaluria_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_EXAMENES')")
     */
    public function show(ExamenOrinaCristaluria $examenOrinaCristaluria,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaCristaluria->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    return $this->render('examen_orina_cristaluria/show.html.twig', [
                        'examen_orina_cristaluria' => $examenOrinaCristaluria,
                        'examen_solicitado' => $examen_solicitado,
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        return $this->render('examen_orina_cristaluria/show.html.twig', [
            'examen_orina_cristaluria' => $examenOrinaCristaluria,
            'examen_solicitado' => $examen_solicitado,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_orina_cristaluria_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_EXAMENES')")
     */
    public function edit(Request $request, ExamenOrinaCristaluria $examenOrinaCristaluria,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaCristaluria->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(examenOrinaCristaluriaType::class, $examenOrinaCristaluria);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Examen modificado con exito');
                        return $this->redirectToRoute('examen_orina_cristaluria_index', [
                            'id' => $examenOrinaCristaluria->getId(),
                            'examen_solicitado' => $examen_solicitado->getId(),
                        ]);
                    }
                    return $this->render('examen_orina_cristaluria/edit.html.twig', [
                        'examen_orina_cristaluria' => $examenOrinaCristaluria,
                        'examen_solicitado' => $examen_solicitado,
                        'editar'            => $editar,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        
        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $editar = true;
            $form = $this->createForm(examenOrinaCristaluriaType::class, $examenOrinaCristaluria);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Examen modificado con exito');
                return $this->redirectToRoute('examen_orina_cristaluria_index', [
                    'id' => $examenOrinaCristaluria->getId(),
                    'examen_solicitado' => $examen_solicitado->getId(),
                ]);
            }
            return $this->render('examen_orina_cristaluria/edit.html.twig', [
                'examen_orina_cristaluria' => $examenOrinaCristaluria,
                'examen_solicitado' => $examen_solicitado,
                'editar'            => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_cristaluria_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_EXAMENES')")
     */
    
    public function delete(Request $request, examenOrinaCristaluria $examenOrinaCristaluria,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaCristaluria->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$examenOrinaCristaluria->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenOrinaCristaluria);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con exito');
                    return $this->redirectToRoute('examen_orina_cristaluria_index',['examen_solicitado' => $examen_solicitado->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }

       if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$examenOrinaCristaluria->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($examenOrinaCristaluria);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Examen eliminado con exito');
            return $this->redirectToRoute('examen_orina_cristaluria_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
