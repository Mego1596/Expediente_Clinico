<?php

namespace App\Controller;

use App\Entity\HistoriaMedica;
use App\Entity\Cita;
use App\Form\HistoriaMedicaType;
use App\Repository\HistoriaMedicaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/historia/medica")
 */
class HistoriaMedicaController extends AbstractController
{
    /**
     * @Route("/{citum}", name="historia_medica_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_HISTORIA_MEDICA')")
     */
    public function index(HistoriaMedicaRepository $historiaMedicaRepository, Cita $citum, Security $AuthUser): Response
    {
        $diagnostico=null;
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId()){
                if($citum->getExpediente()->getHabilitado()){
                    $result = $this->getDoctrine()->getRepository(HistoriaMedica::class)->findBy(['cita_id' => $citum->getId()]);
                    if(count($result) > 0){
                        $em = $this->getDoctrine()->getManager();
                        $RAW_QUERY = "SELECT id10 as codigo, dec10 as descripcion FROM historia_medica_codigo_internacional as h_c_i, 
                                      codigo_internacional as c_i WHERE
                                      h_c_i.codigo_internacional_id = c_i.id AND                       
                                      h_c_i.historia_medica_id=".$result[0]->getId();
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->execute();
                        $diagnostico = $statement->fetchAll();
                    }
                    return $this->render('historia_medica/index.html.twig', [
                        'historia_medicas' => $result,
                        'diagnostico'    => $diagnostico,
                        'user'           => $AuthUser,
                        'cita'           => $citum,
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
        $result = $this->getDoctrine()->getRepository(HistoriaMedica::class)->findBy(['cita' => $citum->getId()]);
        if(count($result) > 0){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT id10 as codigo, dec10 as descripcion FROM historia_medica_codigo_internacional as h_c_i, 
                          codigo_internacional as c_i WHERE
                          h_c_i.codigo_internacional_id = c_i.id AND                       
                          h_c_i.historia_medica_id=".$result[0]->getId();
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $diagnostico = $statement->fetchAll();
        }
        return $this->render('historia_medica/index.html.twig', [
            'historia_medicas' => $result,
            'diagnostico'    => $diagnostico,
            'user'           => $AuthUser,
            'cita'           => $citum,
        ]);
    }

    /**
     * @Route("/new/{citum}", name="historia_medica_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_HISTORIA_MEDICA')")
     */
    public function new(Request $request, Cita $citum, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId()){
                if($citum->getExpediente()->getHabilitado()){

                    $editar = false;
                    $historiaMedica = new HistoriaMedica();
                    $form = $this->createForm(HistoriaMedicaType::class, $historiaMedica);
                    $form->handleRequest($request);
                    //VALIDACION INICIAL PARA LA RUTA ES QUE SI LA CITA YA TIENE UN REGISTRO DE SIGNO VITAL BLOQUEARLA
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT * FROM `historia_medica` WHERE cita_id =".$citum->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if($result == null){
                        if ($form->isSubmitted() && $form->isValid()) {
                            if($form["consultaPor"]->getData() != ""){
                                if($form["signos"]->getData() != ""){
                                    if($form["sintomas"]->getData() != ""){
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $historiaMedica->setCita($citum);
                                        $historiaMedica->setConsultaPor($form["consultaPor"]->getData());
                                        $historiaMedica->setSignos($form["signos"]->getData());
                                        $historiaMedica->setSintomas($form["sintomas"]->getData());
                                        foreach ($form["id10"]->getData() as $codigo) {
                                            $historiaMedica->addId10($codigo);    
                                        }
                                        //$form["diagnostico"]->getData()->getCodigoCategoria()[0]
                                        //$form["codigoEspecifico"]->getData()[0]
                                        //$form["diagnostico"]->getData()->getCodigoCategoria()[4];
                                        /*if($form["diagnostico"]->getData()->getCodigoCategoria()[0] == $form["codigoEspecifico"]->getData()[0] && (int) ($form["diagnostico"]->getData()->getCodigoCategoria()[1].$form["diagnostico"]->getData()->getCodigoCategoria()[2]) >=0 && (int) ($form["diagnostico"]->getData()->getCodigoCategoria()[1].$form["diagnostico"]->getData()->getCodigoCategoria()[2]) <= 99 ){
                                            
                                        }elseif($form["diagnostico"]->getData()->getCodigoCategoria()[4] == $form["codigoEspecifico"]->getData()[0]){
                                            $this->addFlash('fail', 'El código seleccionado no es valido para la categoria elegida');
                                            return $this->render('historia_medica/new.html.twig', [
                                                'historia_medica' => $historiaMedica,
                                                'editar'          => $editar,
                                                'cita'            => $citum,
                                                'form' => $form->createView(),
                                            ]);
                                        }*/
                                        //$historiaMedica->setCodigoEspecifico($form["codigoEspecifico"]->getData());
                                        $entityManager->persist($historiaMedica);
                                        $entityManager->flush();
                                    }else{
                                        $this->addFlash('fail', 'Error, los síntomas no pueden ir vacíos');
                                        return $this->render('historia_medica/new.html.twig', [
                                            'historia_medica' => $historiaMedica,
                                            'editar'          => $editar,
                                            'cita'            => $citum,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, los signos no pueden ir vacíos');
                                    return $this->render('historia_medica/new.html.twig', [
                                        'historia_medica' => $historiaMedica,
                                        'editar'          => $editar,
                                        'cita'            => $citum,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error,el motivo de la consulta no puede ir vacío');
                                 return $this->render('historia_medica/new.html.twig', [
                                        'historia_medica' => $historiaMedica,
                                        'editar'          => $editar,
                                        'cita'            => $citum,
                                        'form' => $form->createView(),
                                    ]);
                            }

                            $this->addFlash('success', 'Historia Médica añadida con éxito');
                            return $this->redirectToRoute('historia_medica_index',['citum' => $citum->getId()]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, no se puede registrar historias médicas diferentes por favor modifique el registro ya ingresado');
                        return $this->redirectToRoute('historia_medica_index',['citum' => $citum->getId()]);
                    }
                    
                    return $this->render('historia_medica/new.html.twig', [
                        'historia_medica' => $historiaMedica,
                        'editar'          => $editar,
                        'cita'            => $citum,
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

        

        $editar = false;
        $historiaMedica = new HistoriaMedica();
        $form = $this->createForm(HistoriaMedicaType::class, $historiaMedica);
        $form->handleRequest($request);
        //VALIDACION INICIAL PARA LA RUTA ES QUE SI LA CITA YA TIENE UN REGISTRO DE SIGNO VITAL BLOQUEARLA
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT * FROM `historia_medica` WHERE cita_id =".$citum->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        if($result == null){
            if ($form->isSubmitted() && $form->isValid()) {
                if($form["consultaPor"]->getData() != ""){
                    if($form["signos"]->getData() != ""){
                        if($form["sintomas"]->getData() != ""){
                            //INICIO PROCESAMIENTO DE DATOS
                            $entityManager = $this->getDoctrine()->getManager();
                            $historiaMedica->setCita($citum);
                            $historiaMedica->setConsultaPor($form["consultaPor"]->getData());
                            $historiaMedica->setSignos($form["signos"]->getData());
                            $historiaMedica->setSintomas($form["sintomas"]->getData());
                            foreach ($form["id10"]->getData() as $codigo) {
                                $historiaMedica->addId10($codigo);    
                            }
                            $entityManager->persist($historiaMedica);
                            $entityManager->flush();
                        }else{
                            $this->addFlash('fail', 'Error, los síntomas no pueden ir vacíos');
                            return $this->render('historia_medica/new.html.twig', [
                                'historia_medica' => $historiaMedica,
                                'editar'          => $editar,
                                'cita'            => $citum,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, los signos no pueden ir vacíos');
                        return $this->render('historia_medica/new.html.twig', [
                            'historia_medica' => $historiaMedica,
                            'editar'          => $editar,
                            'cita'            => $citum,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error,el motivo de la consulta no puede ir vacío');
                     return $this->render('historia_medica/new.html.twig', [
                            'historia_medica' => $historiaMedica,
                            'editar'          => $editar,
                            'cita'            => $citum,
                            'form' => $form->createView(),
                        ]);
                }

                $this->addFlash('success', 'Historia Médica añadida con éxito');
                return $this->redirectToRoute('historia_medica_index',['citum' => $citum->getId()]);
            }
        }else{
            $this->addFlash('fail', 'Error, no se puede registrar historias médicas diferentes por favor modifique el registro ya ingresado');
            return $this->redirectToRoute('historia_medica_index',['citum' => $citum->getId()]);
        }
        
        return $this->render('historia_medica/new.html.twig', [
            'historia_medica' => $historiaMedica,
            'editar'          => $editar,
            'cita'            => $citum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{citum}/edit", name="historia_medica_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_HISTORIA_MEDICA')")
     */
    public function edit(Request $request, HistoriaMedica $historiaMedica, Cita $citum, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId() && $historiaMedica->getCita()->getId() == $citum->getId() ){
                if($citum->getExpediente()->getHabilitado()){
                    $editar=true;        
                    $form = $this->createForm(HistoriaMedicaType::class, $historiaMedica);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Historia Médica modificada con éxito');
                        return $this->redirectToRoute('historia_medica_index', [
                            'citum' => $citum->getId(),
                        ]);
                    }

                    return $this->render('historia_medica/edit.html.twig', [
                        'historia_medica' => $historiaMedica,
                        'editar'          => $editar,
                        'cita'            => $citum,
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

        $editar=true;        
        $form = $this->createForm(HistoriaMedicaType::class, $historiaMedica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Historia Médica modificada con éxito');
            return $this->redirectToRoute('historia_medica_index', [
                'citum' => $citum->getId(),
            ]);
        }

        return $this->render('historia_medica/edit.html.twig', [
            'historia_medica' => $historiaMedica,
            'editar'          => $editar,
            'cita'            => $citum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{citum}", name="historia_medica_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_HISTORIA_MEDICA')")
     */
    public function delete(Request $request, HistoriaMedica $historiaMedica, Cita $citum, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $citum->getExpediente()->getUsuario()->getClinica()->getId() && $historiaMedica->getCita()->getId() == $citum->getId() ){
                if($citum->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$historiaMedica->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($historiaMedica);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Historia Médica eliminada con éxito');
                    return $this->redirectToRoute('historia_medica_index',['citum' => $citum->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        if($citum->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$historiaMedica->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($historiaMedica);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Historia Médica eliminada con éxito');
            return $this->redirectToRoute('historia_medica_index',['citum' => $citum->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
