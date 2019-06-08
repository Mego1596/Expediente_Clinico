<?php

namespace App\Controller;

use App\Entity\SignoVital;
use App\Entity\Cita;
use App\Form\SignoVitalType;
use App\Repository\SignoVitalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/signo/vital")
 */
class SignoVitalController extends AbstractController
{
    /**
     * @Route("/{cita}", name="signo_vital_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_SIGNO_VITAL')")
     */
    public function index(SignoVitalRepository $signoVitalRepository, Security $AuthUser, Cita $cita): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId()){
                if($cita->getExpediente()->getHabilitado()){
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT id, peso,temperatura,estatura,presion_arterial as presionArterial, ritmo_cardiaco as ritmoCardiaco FROM `signo_vital` WHERE cita_id =".$cita->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    return $this->render('signo_vital/index.html.twig', [
                        'signos_vitales' => $result,
                        'user'           => $AuthUser,
                        'cita'           => $cita,
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

        if($cita->getExpediente()->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT id, peso,temperatura,estatura,presion_arterial as presionArterial, ritmo_cardiaco as ritmoCardiaco FROM `signo_vital` WHERE cita_id =".$cita->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('signo_vital/index.html.twig', [
                'signos_vitales' => $result,
                'user'           => $AuthUser,
                'cita'           => $cita,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        } 
        
    }

    /**
     * @Route("/new/{cita}", name="signo_vital_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_SIGNO_VITAL')")
     */
    public function new(Request $request, Cita $cita, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId()){
                if($cita->getExpediente()->getHabilitado()){
                    //VALIDACION INICIAL PARA LA RUTA ES QUE SI LA CITA YA TIENE UN REGISTRO DE SIGNO VITAL BLOQUEARLA
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT * FROM `signo_vital` WHERE cita_id =".$cita->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if($result == null){
                        $editar = false;
                        $signoVital = new SignoVital();
                        $form = $this->createForm(SignoVitalType::class, $signoVital);
                        $form->handleRequest($request);

                        if ($form->isSubmitted() && $form->isValid()) {
                            if($form["peso"]->getData() != ""){
                                if($form["temperatura"]->getData() != ""){
                                    if($form["estatura"]->getData() != ""){
                                        if($form["presionArterial"]->getData() != ""){
                                            if($form["ritmoCardiaco"]->getData() != ""){
                                                //INICIO DE PROCESO DE DATOS
                                                $entityManager = $this->getDoctrine()->getManager();
                                                $signoVital->setCita($cita);
                                                $entityManager->persist($signoVital);
                                                $entityManager->flush();
                                                //FIN DE PROCESO DE DATOS
                                            }else{
                                               $this->addFlash('fail', 'Error, el ritmo cardiaco del paciente no puede estar vacio');
                                                return $this->render('signo_vital/new.html.twig', [
                                                    'signo_vital' => $signoVital,
                                                    'cita'           => $cita,
                                                    'editar'         => $editar,
                                                    'form' => $form->createView(),
                                                ]);
                                            }
                                        }else{
                                           $this->addFlash('fail', 'Error, la presion arterial del paciente no puede estar vacia');
                                            return $this->render('signo_vital/new.html.twig', [
                                                'signo_vital' => $signoVital,
                                                'cita'           => $cita,
                                                'editar'         => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, la estatura del paciente no puede estar vacia');
                                        return $this->render('signo_vital/new.html.twig', [
                                            'signo_vital' => $signoVital,
                                            'cita'           => $cita,
                                            'editar'         => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, la temperatura del paciente no puede estar vacia');
                                    return $this->render('signo_vital/new.html.twig', [
                                        'signo_vital' => $signoVital,
                                        'cita'           => $cita,
                                        'editar'         => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, el peso del paciente no puede estar vacio');
                                return $this->render('signo_vital/new.html.twig', [
                                    'signo_vital' => $signoVital,
                                    'cita'           => $cita,
                                    'editar'         => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                            $this->addFlash('success', 'Signos Vitales añadidos con éxito');
                            return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, no se puede registrar signos vitales diferentes por favor modifique el registro ya ingresado');
                        return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
                    }

                    return $this->render('signo_vital/new.html.twig', [
                        'signo_vital' => $signoVital,
                        'cita'           => $cita,
                        'editar'         => $editar,
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

        if($cita->getExpediente()->getHabilitado()){
            //VALIDACION INICIAL PARA LA RUTA ES QUE SI LA CITA YA TIENE UN REGISTRO DE SIGNO VITAL BLOQUEARLA
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT * FROM `signo_vital` WHERE cita_id =".$cita->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if($result == null){
                $editar = false;
                $signoVital = new SignoVital();
                $form = $this->createForm(SignoVitalType::class, $signoVital);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    if($form["peso"]->getData() != ""){
                        if($form["temperatura"]->getData() != ""){
                            if($form["estatura"]->getData() != ""){
                                if($form["presionArterial"]->getData() != ""){
                                    if($form["ritmoCardiaco"]->getData() != ""){
                                        //INICIO DE PROCESO DE DATOS
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $signoVital->setCita($cita);
                                        $entityManager->persist($signoVital);
                                        $entityManager->flush();
                                        //FIN DE PROCESO DE DATOS
                                    }else{
                                       $this->addFlash('fail', 'Error, el ritmo cardiaco del paciente no puede estar vacio');
                                        return $this->render('signo_vital/new.html.twig', [
                                            'signo_vital' => $signoVital,
                                            'cita'           => $cita,
                                            'editar'         => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                   $this->addFlash('fail', 'Error, la presion arterial del paciente no puede estar vacia');
                                    return $this->render('signo_vital/new.html.twig', [
                                        'signo_vital' => $signoVital,
                                        'cita'           => $cita,
                                        'editar'         => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, la estatura del paciente no puede estar vacia');
                                return $this->render('signo_vital/new.html.twig', [
                                    'signo_vital' => $signoVital,
                                    'cita'           => $cita,
                                    'editar'         => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, la temperatura del paciente no puede estar vacia');
                            return $this->render('signo_vital/new.html.twig', [
                                'signo_vital' => $signoVital,
                                'cita'           => $cita,
                                'editar'         => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, el peso del paciente no puede estar vacio');
                        return $this->render('signo_vital/new.html.twig', [
                            'signo_vital' => $signoVital,
                            'cita'           => $cita,
                            'editar'         => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                    $this->addFlash('success', 'Signos Vitales añadidos con éxito');
                    return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
                }
            }else{
                $this->addFlash('fail', 'Error, no se puede registrar signos vitales diferentes por favor modifique el registro ya ingresado');
                return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
            }

            return $this->render('signo_vital/new.html.twig', [
                'signo_vital' => $signoVital,
                'cita'           => $cita,
                'editar'         => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        

    }

    /**
     * @Route("/{id}/{cita}", name="signo_vital_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_SIGNO_VITAL')")
     */
    public function show(SignoVital $signoVital, Cita $cita, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId() && $signoVital->getCita()->getId() == $cita->getId() ){
                if($cita->getExpediente()->getHabilitado()){
                    return $this->render('signo_vital/show.html.twig', [
                        'signo_vital' => $signoVital,
                        'cita'           => $cita,
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

        if($cita->getExpediente()->getHabilitado()){
            return $this->render('signo_vital/show.html.twig', [
                'signo_vital' => $signoVital,
                'cita'           => $cita,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        
    }

    /**
     * @Route("/{id}/{cita}/edit", name="signo_vital_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_SIGNO_VITAL')")
     */
    public function edit(Request $request, SignoVital $signoVital, Cita $cita, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId() && $signoVital->getCita()->getId() == $cita->getId() ){
                if($cita->getExpediente()->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(SignoVitalType::class, $signoVital);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Signos Vitales modificados con éxito');
                        return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
                    }

                    return $this->render('signo_vital/edit.html.twig', [
                        'signo_vital' => $signoVital,
                        'cita'           => $cita,
                        'editar'         => $editar,
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

        if($cita->getExpediente()->getHabilitado()){
            $editar = true;
            $form = $this->createForm(SignoVitalType::class, $signoVital);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Signos Vitales modificados con éxito');
                return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
            }

            return $this->render('signo_vital/edit.html.twig', [
                'signo_vital' => $signoVital,
                'cita'           => $cita,
                'editar'         => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        
    }

    /**
     * @Route("/{id}/{cita}", name="signo_vital_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_SIGNO_VITAL')")
     */
    public function delete(Request $request, SignoVital $signoVital, Cita $cita, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId() && $AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId() && $signoVital->getCita()->getId() == $cita->getId() ){
                if($cita->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$signoVital->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($signoVital);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Signos Vitales eliminados con éxito');
                    return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        }
        if($cita->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$signoVital->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($signoVital);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Signos Vitales eliminados con éxito');
            return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }

    }
}
