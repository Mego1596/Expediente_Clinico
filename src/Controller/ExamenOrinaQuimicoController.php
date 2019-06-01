<?php

namespace App\Controller;

use App\Entity\ExamenOrinaQuimico;
use App\Form\ExamenOrinaQuimicoType;
use App\Repository\ExamenOrinaQuimicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/orina/quimico")
 */
class ExamenOrinaQuimicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_orina_quimico_index", methods={"GET"})
     */
    public function index(examenOrinaQuimicoRepository $examenOrinaQuimicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){

                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_orina_quimico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    return $this->render('examen_orina_quimico/index.html.twig', [
                        'examen_orina_quimicos'    => $result,
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
            $RAW_QUERY = "SELECT examen.* FROM `examen_orina_quimico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('examen_orina_quimico/index.html.twig', [
                'examen_orina_quimicos'    => $result,
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
     * @Route("/new/{examen_solicitado}", name="examen_orina_quimico_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = false;
                    $examenOrinaQuimico = new examenOrinaQuimico();
                    $form = $this->createForm(examenOrinaQuimicoType::class, $examenOrinaQuimico);
                    $form->handleRequest($request);

                    //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_orina_quimico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if(count($result) < 1){
                        if ($form->isSubmitted() && $form->isValid()) {
                            if($form["densidad"]->getData() != ""){
                                if($form["ph"]->getData() != ""){
                                    if($form["glucosa"]->getData() != ""){
                                        if($form["proteinas"]->getData() != ""){
                                            if($form["hemoglobina"]->getData() != ""){
                                                if($form["cuerpoCetonico"]->getData() != ""){
                                                    if($form["pigmentoBiliar"]->getData() != ""){
                                                        if($form["urobilinogeno"]->getData() != ""){
                                                            if($form["nitritos"]->getData() != ""){
                                                                //PROCESAMIENTO DE DATOS
                                                                $entityManager = $this->getDoctrine()->getManager();
                                                                $examenOrinaQuimico->setExamenSolicitado($examen_solicitado);
                                                                $entityManager->persist($examenOrinaQuimico);
                                                                $entityManager->flush();
                                                                //FIN DE PROCESAMIENTO DE DATOS
                                                            }else{
                                                                $this->addFlash('fail', 'Error, los nitritos no puede ir vacio');
                                                                return $this->render('examen_orina_quimico/new.html.twig', [
                                                                    'examen_orina_quimico' => $examenOrinaQuimico,
                                                                    'examen_solicitado' => $examen_solicitado,
                                                                    'editar'            => $editar,
                                                                    'form' => $form->createView(),
                                                                ]); 
                                                            }
                                                        }else{
                                                           $this->addFlash('fail', 'Error, Urobilinogeno no puede ir vacio');
                                                            return $this->render('examen_orina_quimico/new.html.twig', [
                                                                'examen_orina_quimico' => $examenOrinaQuimico,
                                                                'examen_solicitado' => $examen_solicitado,
                                                                'editar'            => $editar,
                                                                'form' => $form->createView(),
                                                            ]); 
                                                        }
                                                    }else{
                                                        $this->addFlash('fail', 'Error, el pigmento biliar no puede ir vacio');
                                                        return $this->render('examen_orina_quimico/new.html.twig', [
                                                            'examen_orina_quimico' => $examenOrinaQuimico,
                                                            'examen_solicitado' => $examen_solicitado,
                                                            'editar'            => $editar,
                                                            'form' => $form->createView(),
                                                        ]);
                                                    }
                                                }else{
                                                    $this->addFlash('fail', 'Error, el cuerpo cetonico no puede ir vacio');
                                                    return $this->render('examen_orina_quimico/new.html.twig', [
                                                        'examen_orina_quimico' => $examenOrinaQuimico,
                                                        'examen_solicitado' => $examen_solicitado,
                                                        'editar'            => $editar,
                                                        'form' => $form->createView(),
                                                    ]);
                                                }
                                            }else{
                                                $this->addFlash('fail', 'Error, la hemoglobina no puede ir vacia');
                                                return $this->render('examen_orina_quimico/new.html.twig', [
                                                    'examen_orina_quimico' => $examenOrinaQuimico,
                                                    'examen_solicitado' => $examen_solicitado,
                                                    'editar'            => $editar,
                                                    'form' => $form->createView(),
                                                ]);
                                            }
                                        }else{
                                            $this->addFlash('fail', 'Error,las  proteinas no pueden ir vacias');
                                            return $this->render('examen_orina_quimico/new.html.twig', [
                                                'examen_orina_quimico' => $examenOrinaQuimico,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, la glucosa no puede ir vacia');
                                        return $this->render('examen_orina_quimico/new.html.twig', [
                                            'examen_orina_quimico' => $examenOrinaQuimico,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, el ph no puede ir vacio');
                                    return $this->render('examen_orina_quimico/new.html.twig', [
                                        'examen_orina_quimico' => $examenOrinaQuimico,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, la densidad no puede ir vacia');
                                return $this->render('examen_orina_quimico/new.html.twig', [
                                    'examen_orina_quimico' => $examenOrinaQuimico,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                            $this->addFlash('success', 'Examen añadido con exito');
                            return $this->redirectToRoute('examen_orina_quimico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o eliminelo si desea crear uno nuevo.');
                        return $this->redirectToRoute('examen_orina_quimico_index', ['examen_solicitado' => $examen_solicitado->getId()]);
                    }

                    return $this->render('examen_orina_quimico/new.html.twig', [
                        'examen_orina_quimico' => $examenOrinaQuimico,
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
            $examenOrinaQuimico = new examenOrinaQuimico();
            $form = $this->createForm(examenOrinaQuimicoType::class, $examenOrinaQuimico);
            $form->handleRequest($request);

            //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_orina_quimico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if(count($result) < 1){
                if ($form->isSubmitted() && $form->isValid()) {
                    if($form["densidad"]->getData() != ""){
                        if($form["ph"]->getData() != ""){
                            if($form["glucosa"]->getData() != ""){
                                if($form["proteinas"]->getData() != ""){
                                    if($form["hemoglobina"]->getData() != ""){
                                        if($form["cuerpoCetonico"]->getData() != ""){
                                            if($form["pigmentoBiliar"]->getData() != ""){
                                                if($form["urobilinogeno"]->getData() != ""){
                                                    if($form["nitritos"]->getData() != ""){
                                                        //PROCESAMIENTO DE DATOS
                                                        $entityManager = $this->getDoctrine()->getManager();
                                                        $examenOrinaQuimico->setExamenSolicitado($examen_solicitado);
                                                        $entityManager->persist($examenOrinaQuimico);
                                                        $entityManager->flush();
                                                        //FIN DE PROCESAMIENTO DE DATOS
                                                    }else{
                                                        $this->addFlash('fail', 'Error, los nitritos no puede ir vacio');
                                                        return $this->render('examen_orina_quimico/new.html.twig', [
                                                            'examen_orina_quimico' => $examenOrinaQuimico,
                                                            'examen_solicitado' => $examen_solicitado,
                                                            'editar'            => $editar,
                                                            'form' => $form->createView(),
                                                        ]); 
                                                    }
                                                }else{
                                                   $this->addFlash('fail', 'Error, Urobilinogeno no puede ir vacio');
                                                    return $this->render('examen_orina_quimico/new.html.twig', [
                                                        'examen_orina_quimico' => $examenOrinaQuimico,
                                                        'examen_solicitado' => $examen_solicitado,
                                                        'editar'            => $editar,
                                                        'form' => $form->createView(),
                                                    ]); 
                                                }
                                            }else{
                                                $this->addFlash('fail', 'Error, el pigmento biliar no puede ir vacio');
                                                return $this->render('examen_orina_quimico/new.html.twig', [
                                                    'examen_orina_quimico' => $examenOrinaQuimico,
                                                    'examen_solicitado' => $examen_solicitado,
                                                    'editar'            => $editar,
                                                    'form' => $form->createView(),
                                                ]);
                                            }
                                        }else{
                                            $this->addFlash('fail', 'Error, el cuerpo cetonico no puede ir vacio');
                                            return $this->render('examen_orina_quimico/new.html.twig', [
                                                'examen_orina_quimico' => $examenOrinaQuimico,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, la hemoglobina no puede ir vacia');
                                        return $this->render('examen_orina_quimico/new.html.twig', [
                                            'examen_orina_quimico' => $examenOrinaQuimico,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error,las  proteinas no pueden ir vacias');
                                    return $this->render('examen_orina_quimico/new.html.twig', [
                                        'examen_orina_quimico' => $examenOrinaQuimico,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, la glucosa no puede ir vacia');
                                return $this->render('examen_orina_quimico/new.html.twig', [
                                    'examen_orina_quimico' => $examenOrinaQuimico,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, el ph no puede ir vacio');
                            return $this->render('examen_orina_quimico/new.html.twig', [
                                'examen_orina_quimico' => $examenOrinaQuimico,
                                'examen_solicitado' => $examen_solicitado,
                                'editar'            => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, la densidad no puede ir vacia');
                        return $this->render('examen_orina_quimico/new.html.twig', [
                            'examen_orina_quimico' => $examenOrinaQuimico,
                            'examen_solicitado' => $examen_solicitado,
                            'editar'            => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                    $this->addFlash('success', 'Examen añadido con exito');
                    return $this->redirectToRoute('examen_orina_quimico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                }
            }else{
                $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o eliminelo si desea crear uno nuevo.');
                return $this->redirectToRoute('examen_orina_quimico_index', ['examen_solicitado' => $examen_solicitado->getId()]);
            }

            return $this->render('examen_orina_quimico/new.html.twig', [
                'examen_orina_quimico' => $examenOrinaQuimico,
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
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_quimico_show", methods={"GET"})
     */
    public function show(examenOrinaQuimico $examenOrinaQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaQuimico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    return $this->render('examen_orina_quimico/show.html.twig', [
                        'examen_orina_quimico' => $examenOrinaQuimico,
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
        return $this->render('examen_orina_quimico/show.html.twig', [
            'examen_orina_quimico' => $examenOrinaQuimico,
            'examen_solicitado' => $examen_solicitado,
        ]);
    }
    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_orina_quimico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, examenOrinaQuimico $examenOrinaQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaQuimico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(examenOrinaQuimicoType::class, $examenOrinaQuimico);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Examen modificado con exito');
                        return $this->redirectToRoute('examen_orina_quimico_index', [
                            'id' => $examenOrinaQuimico->getId(),
                            'examen_solicitado' => $examen_solicitado->getId(),
                        ]);
                    }
                    return $this->render('examen_orina_quimico/edit.html.twig', [
                        'examen_orina_quimico' => $examenOrinaQuimico,
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
            $form = $this->createForm(examenOrinaQuimicoType::class, $examenOrinaQuimico);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Examen modificado con exito');
                return $this->redirectToRoute('examen_orina_quimico_index', [
                    'id' => $examenOrinaQuimico->getId(),
                    'examen_solicitado' => $examen_solicitado->getId(),
                ]);
            }
            return $this->render('examen_orina_quimico/edit.html.twig', [
                'examen_orina_quimico' => $examenOrinaQuimico,
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
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_quimico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, examenOrinaQuimico $examenOrinaQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaQuimico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$examenOrinaQuimico->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenOrinaQuimico);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con exito');
                    return $this->redirectToRoute('examen_orina_quimico_index',['examen_solicitado' => $examen_solicitado->getId()]);
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
            if ($this->isCsrfTokenValid('delete'.$examenOrinaQuimico->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($examenOrinaQuimico);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Examen eliminado con exito');
            return $this->redirectToRoute('examen_orina_quimico_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
