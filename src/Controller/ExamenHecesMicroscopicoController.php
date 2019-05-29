<?php

namespace App\Controller;

use App\Entity\ExamenHecesMicroscopico;
use App\Entity\ExamenSolicitado;
use App\Form\ExamenHecesMicroscopicoType;
use App\Repository\ExamenHecesMicroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/heces/microscopico")
 */
class ExamenHecesMicroscopicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_heces_microscopico_index", methods={"GET"})
     */
    public function index(ExamenHecesMicroscopicoRepository $examenHecesMicroscopicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){

                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_heces_microscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    return $this->render('examen_heces_microscopico/index.html.twig', [
                        'examen_heces_microscopicos'    => $result,
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
            $RAW_QUERY = "SELECT examen.* FROM `examen_heces_microscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('examen_heces_microscopico/index.html.twig', [
                'examen_heces_microscopicos'    => $result,
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
     * @Route("/new/{examen_solicitado}", name="examen_heces_microscopico_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = false;
                    $examenHecesMicroscopico = new ExamenHecesMicroscopico();
                    $form = $this->createForm(ExamenHecesMicroscopicoType::class, $examenHecesMicroscopico);
                    $form->handleRequest($request);

                    //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_heces_microscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if(count($result) < 1){
                        if ($form->isSubmitted() && $form->isValid()) {
                            if($form["hematies"]->getData() != ""){
                                if($form["leucocito"]->getData() != ""){
                                    if($form["floraBacteriana"]->getData() != ""){
                                        if($form["levadura"]->getData() != ""){
                                            //PROCESAMIENTO DE DATOS
                                            $entityManager = $this->getDoctrine()->getManager();
                                            $examenHecesMicroscopico->setExamenSolicitado($examen_solicitado);
                                            $entityManager->persist($examenHecesMicroscopico);
                                            $entityManager->flush();
                                            //FIN DE PROCESAMIENTO DE DATOS

                                        }else{
                                            $this->addFlash('fail', 'Error, hematies no puede ir vacio, si no hay resultados que asignar por favor asigne " * " ');
                                            return $this->render('examen_heces_microscopico/new.html.twig', [
                                                'examen_heces_microscopico' => $examenHecesMicroscopico,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, Leucocitos no puede ir vacio, si no hay resultados que asignar por favor asigne " * "');
                                        return $this->render('examen_heces_microscopico/new.html.twig', [
                                            'examen_heces_microscopico' => $examenHecesMicroscopico,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, Flora Bacteriana no puede ir vacio, si no hay resultados que asignar por favor asigne " * "');
                                    return $this->render('examen_heces_microscopico/new.html.twig', [
                                        'examen_heces_microscopico' => $examenHecesMicroscopico,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, Levadura no puede ir vacio, si no hay resultados que asignar por favor asigne " * "');
                                return $this->render('examen_heces_microscopico/new.html.twig', [
                                    'examen_heces_microscopico' => $examenHecesMicroscopico,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                            $this->addFlash('success', 'Examen añadido con exito');
                            return $this->redirectToRoute('examen_heces_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o eliminelo si desea crear uno nuevo.');
                        return $this->redirectToRoute('examen_heces_microscopico_index', ['examen_solicitado' => $examen_solicitado->getId()]);
                    }

                    return $this->render('examen_heces_microscopico/new.html.twig', [
                        'examen_heces_microscopico' => $examenHecesMicroscopico,
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



        $editar = false;
        $examenHecesMicroscopico = new ExamenHecesMicroscopico();
        $form = $this->createForm(ExamenHecesMicroscopicoType::class, $examenHecesMicroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form["hematies"]->getData() != ""){
                if($form["leucocito"]->getData() != ""){
                    if($form["floraBacteriana"]->getData() != ""){
                        if($form["levadura"]->getData() != ""){
                            //PROCESAMIENTO DE DATOS
                            $entityManager = $this->getDoctrine()->getManager();
                            $examenHecesMicroscopico->setExamenSolicitado($examen_solicitado);
                            $entityManager->persist($examenHecesMicroscopico);
                            $entityManager->flush();
                            //FIN DE PROCESAMIENTO DE DATOS

                        }else{
                            $this->addFlash('fail', 'Error, hematies no puede ir vacio, si no hay resultados que asignar por favor asigne " * " ');
                            return $this->render('examen_heces_microscopico/new.html.twig', [
                                'examen_heces_microscopico' => $examenHecesMicroscopico,
                                'examen_solicitado' => $examen_solicitado,
                                'editar'            => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, Leucocitos no puede ir vacio, si no hay resultados que asignar por favor asigne " * "');
                        return $this->render('examen_heces_microscopico/new.html.twig', [
                            'examen_heces_microscopico' => $examenHecesMicroscopico,
                            'examen_solicitado' => $examen_solicitado,
                            'editar'            => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error, Flora Bacteriana no puede ir vacio, si no hay resultados que asignar por favor asigne " * "');
                    return $this->render('examen_heces_microscopico/new.html.twig', [
                        'examen_heces_microscopico' => $examenHecesMicroscopico,
                        'examen_solicitado' => $examen_solicitado,
                        'editar'            => $editar,
                        'form' => $form->createView(),
                    ]);
                }
            }else{
                $this->addFlash('fail', 'Error, Levadura no puede ir vacio, si no hay resultados que asignar por favor asigne " * "');
                return $this->render('examen_heces_microscopico/new.html.twig', [
                    'examen_heces_microscopico' => $examenHecesMicroscopico,
                    'examen_solicitado' => $examen_solicitado,
                    'editar'            => $editar,
                    'form' => $form->createView(),
                ]);
            }
            $this->addFlash('success', 'Examen añadido con exito');
            return $this->redirectToRoute('examen_heces_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }

        return $this->render('examen_heces_microscopico/new.html.twig', [
            'examen_heces_microscopico' => $examenHecesMicroscopico,
            'examen_solicitado' => $examen_solicitado,
            'editar'            => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_microscopico_show", methods={"GET"})
     */
    public function show(ExamenHecesMicroscopico $examenHecesMicroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    return $this->render('examen_heces_microscopico/show.html.twig', [
                        'examen_heces_microscopico' => $examenHecesMicroscopico,
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
        return $this->render('examen_heces_microscopico/show.html.twig', [
            'examen_heces_microscopico' => $examenHecesMicroscopico,
            'examen_solicitado' => $examen_solicitado,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_heces_microscopico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenHecesMicroscopico $examenHecesMicroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(ExamenHecesMicroscopicoType::class, $examenHecesMicroscopico);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Examen modificado con exito');
                        return $this->redirectToRoute('examen_heces_microscopico_index', [
                            'id' => $examenHecesMicroscopico->getId(),
                            'examen_solicitado' => $examen_solicitado->getId(),
                        ]);
                    }
                    return $this->render('examen_heces_microscopico/edit.html.twig', [
                        'examen_heces_microscopico' => $examenHecesMicroscopico,
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
            $form = $this->createForm(ExamenHecesMicroscopicoType::class, $examenHecesMicroscopico);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Examen modificado con exito');
                return $this->redirectToRoute('examen_heces_microscopico_index', [
                    'id' => $examenHecesMicroscopico->getId(),
                    'examen_solicitado' => $examen_solicitado->getId(),
                ]);
            }
            return $this->render('examen_heces_microscopico/edit.html.twig', [
                'examen_heces_microscopico' => $examenHecesMicroscopico,
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
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_microscopico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenHecesMicroscopico $examenHecesMicroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$examenHecesMicroscopico->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenHecesMicroscopico);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con exito');
                    return $this->redirectToRoute('examen_heces_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
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
            if ($this->isCsrfTokenValid('delete'.$examenHecesMicroscopico->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($examenHecesMicroscopico);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Examen eliminado con exito');
            return $this->redirectToRoute('examen_heces_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
