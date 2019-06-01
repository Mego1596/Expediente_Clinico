<?php

namespace App\Controller;

use App\Entity\ExamenQuimicaSanguinea;
use App\Form\ExamenQuimicaSanguineaType;
use App\Repository\ExamenQuimicaSanguineaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/quimica/sanguinea")
 */
class ExamenQuimicaSanguineaController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_quimica_sanguinea_index", methods={"GET"})
     */
    public function index(ExamenQuimicaSanguineaRepository $examenQuimicaSanguineaRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){

                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_quimica_sanguinea` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    return $this->render('examen_quimica_sanguinea/index.html.twig', [
                        'examen_quimica_sanguineas'          => $result,
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
            $RAW_QUERY = "SELECT examen.* FROM `examen_quimica_sanguinea` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('examen_quimica_sanguinea/index.html.twig', [
                'examen_quimica_sanguineas'    => $result,
                'user'                          => $AuthUser,
                'examen_solicitado'             => $examen_solicitado,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_quimica_sanguinea_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = false;
                    $examenQuimicaSanguinea = new examenQuimicaSanguinea();
                    $form = $this->createForm(examenQuimicaSanguineaType::class, $examenQuimicaSanguinea);
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        if($form["parametro"]->getData() != ""){
                            if($form["resultado"]->getData() >= 0){
                                if($form["unidades"]->getData() != ""){
                                    if($form["rango"]->getData() != ""){
                                        if($form["comentario"]->getData() != ""){
                                            //PROCESAMIENTO DE DATOS
                                            $entityManager = $this->getDoctrine()->getManager();
                                            $examenQuimicaSanguinea->setExamenSolicitado($examen_solicitado);
                                            $examenQuimicaSanguinea->setResultado(round($form["resultado"]->getData()));
                                            $entityManager->persist($examenQuimicaSanguinea);
                                            $entityManager->flush();
                                            //FIN DE PROCESAMIENTO DE DATOS
                                        }else{
                                            $this->addFlash('fail', 'Error, digite un comentario no puede ir vacio');
                                            return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                                'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, digite el rango no puede ir vacio');
                                        return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                            'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, las unidades no pueden ir vacias');
                                    return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                        'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, el resultado no puede ser negativo por favor ingrese un numero valido');
                                return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                    'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, el parametro no puede ir vacio');
                            return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                'examen_solicitado' => $examen_solicitado,
                                'editar'            => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                        $this->addFlash('success', 'Examen añadido con exito');
                        return $this->redirectToRoute('examen_quimica_sanguinea_index',['examen_solicitado' => $examen_solicitado->getId()]);
                    }
                    

                    return $this->render('examen_quimica_sanguinea/new.html.twig', [
                        'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
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
            $examenQuimicaSanguinea = new examenQuimicaSanguinea();
            $form = $this->createForm(examenQuimicaSanguineaType::class, $examenQuimicaSanguinea);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if($form["parametro"]->getData() != ""){
                    if($form["resultado"]->getData() >= 0){
                        if($form["unidades"]->getData() != ""){
                            if($form["rango"]->getData() != ""){
                                if($form["comentario"]->getData() != ""){
                                    //PROCESAMIENTO DE DATOS
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $examenQuimicaSanguinea->setExamenSolicitado($examen_solicitado);
                                    $examenQuimicaSanguinea->setResultado(round($form["resultado"]->getData()));
                                    $entityManager->persist($examenQuimicaSanguinea);
                                    $entityManager->flush();
                                    //FIN DE PROCESAMIENTO DE DATOS
                                }else{
                                    $this->addFlash('fail', 'Error, digite un comentario no puede ir vacio');
                                    return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                        'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, digite el rango no puede ir vacio');
                                return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                    'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, las unidades no pueden ir vacias');
                            return $this->render('examen_quimica_sanguinea/new.html.twig', [
                                'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                                'examen_solicitado' => $examen_solicitado,
                                'editar'            => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, el resultado no puede ser negativo por favor ingrese un numero valido');
                        return $this->render('examen_quimica_sanguinea/new.html.twig', [
                            'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                            'examen_solicitado' => $examen_solicitado,
                            'editar'            => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error, el parametro no puede ir vacio');
                    return $this->render('examen_quimica_sanguinea/new.html.twig', [
                        'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
                        'examen_solicitado' => $examen_solicitado,
                        'editar'            => $editar,
                        'form' => $form->createView(),
                    ]);
                }
                $this->addFlash('success', 'Examen añadido con exito');
                return $this->redirectToRoute('examen_quimica_sanguinea_index',['examen_solicitado' => $examen_solicitado->getId()]);
            }
            

            return $this->render('examen_quimica_sanguinea/new.html.twig', [
                'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
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
     * @Route("/{id}/{examen_solicitado}", name="examen_quimica_sanguinea_show", methods={"GET"})
     */
    public function show(ExamenQuimicaSanguinea $examenQuimicaSanguinea,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenQuimicaSanguinea->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    return $this->render('examen_quimica_sanguinea/show.html.twig', [
                        'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
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
        return $this->render('examen_quimica_sanguinea/show.html.twig', [
            'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
            'examen_solicitado' => $examen_solicitado,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_quimica_sanguinea_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenQuimicaSanguinea $examenQuimicaSanguinea,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenQuimicaSanguinea->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(examenQuimicaSanguineaType::class, $examenQuimicaSanguinea);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Examen modificado con exito');
                        return $this->redirectToRoute('examen_quimica_sanguinea_index', [
                            'id' => $examenQuimicaSanguinea->getId(),
                            'examen_solicitado' => $examen_solicitado->getId(),
                        ]);
                    }
                    return $this->render('examen_quimica_sanguinea/edit.html.twig', [
                        'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
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
            $form = $this->createForm(examenQuimicaSanguineaType::class, $examenQuimicaSanguinea);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Examen modificado con exito');
                return $this->redirectToRoute('examen_quimica_sanguinea_index', [
                    'id' => $examenQuimicaSanguinea->getId(),
                    'examen_solicitado' => $examen_solicitado->getId(),
                ]);
            }
            return $this->render('examen_quimica_sanguinea/edit.html.twig', [
                'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
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
     * @Route("/{id}/{examen_solicitado}", name="examen_quimica_sanguinea_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenQuimicaSanguinea $examenQuimicaSanguinea,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenQuimicaSanguinea->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$examenQuimicaSanguinea->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenQuimicaSanguinea);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con exito');
                    return $this->redirectToRoute('examen_quimica_sanguinea_index',['examen_solicitado' => $examen_solicitado->getId()]);
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
            if ($this->isCsrfTokenValid('delete'.$examenQuimicaSanguinea->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($examenQuimicaSanguinea);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Examen eliminado con exito');
            return $this->redirectToRoute('examen_quimica_sanguinea_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }







}
