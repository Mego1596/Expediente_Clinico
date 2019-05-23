<?php

namespace App\Controller;

use App\Entity\Familiar;
use App\Entity\FamiliaresExpediente;
use App\Entity\Expediente;
use App\Form\FamiliarType;
use App\Repository\FamiliarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/familiar")
 */
class FamiliarController extends AbstractController
{
    /**
     * @Route("/{expediente}", name="familiar_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_FAMILIAR')")
     */
    public function index(FamiliarRepository $familiarRepository, Expediente $expediente,Security $AuthUser): Response
    {
        
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT f.*, fex.responsable as responsable FROM familiar as f, familiares_expediente as fex, expediente as exp WHERE exp.id = fex.expediente_id AND fex.familiar_id = f.id AND exp.id = ".$expediente->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    return $this->render('familiar/index.html.twig', [
                        'familiares' => $result,
                        'expediente' => $expediente,
                        'user'       => $AuthUser,
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }

        if($expediente->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT f.*, fex.responsable as responsable FROM familiar as f, familiares_expediente as fex, expediente as exp WHERE exp.id = fex.expediente_id AND fex.familiar_id = f.id AND exp.id = ".$expediente->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->render('familiar/index.html.twig', [
                'familiares' => $result,
                'expediente' => $expediente,
                'user'       => $AuthUser,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }

    }

    /**
     * @Route("/new/{expediente}", name="familiar_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_FAMILIAR')")
     */
    public function new(Request $request, Expediente $expediente, Security $AuthUser): Response
    {

        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId()){
                if($expediente->getHabilitado()){
                    $editar=false;
                    $familiar = new Familiar();
                    $form = $this->createForm(FamiliarType::class, $familiar);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
                        if($form["nombres"]->getData() != ""){
                            if($form["apellidos"]->getData() != ""){
                                if($form["fechaNacimiento"]->getData() != ""){
                                    if($form["telefono"]->getData() != ""){
                                        if($form["descripcion"]->getData() != ""){
                                            if($request->request->get('responsable') != ""){
                                                //CODIGO DE ACCION PARA INGRESAR UN FAMILIAR
                                                $familiar->setNombres($form["nombres"]->getData());
                                                $familiar->setApellidos($form["apellidos"]->getData());
                                                $familiar->setFechaNacimiento($form["fechaNacimiento"]->getData());
                                                $familiar->setTelefono($form["telefono"]->getData());
                                                $familiar->setDescripcion($form["descripcion"]->getData());
                                                $entityManager->persist($familiar);
                                                $familiaresExpediente = new FamiliaresExpediente();
                                                $familiaresExpediente->setExpediente($expediente);
                                                $familiaresExpediente->setFamiliar($familiar);
                                                $familiaresExpediente->setResponsable($request->request->get('responsable'));
                                                $entityManager->persist($familiaresExpediente);
                                                $entityManager->flush();
                                            }else{
                                                $familiar->setNombres($form["nombres"]->getData());
                                                $familiar->setApellidos($form["apellidos"]->getData());
                                                $familiar->setFechaNacimiento($form["fechaNacimiento"]->getData());
                                                $familiar->setTelefono($form["telefono"]->getData());
                                                $familiar->setDescripcion($form["descripcion"]->getData());
                                                $entityManager->persist($familiar);
                                                $familiaresExpediente = new FamiliaresExpediente();
                                                $familiaresExpediente->setExpediente($expediente);
                                                $familiaresExpediente->setFamiliar($familiar);
                                                $familiaresExpediente->setResponsable(false);
                                                $entityManager->persist($familiaresExpediente);
                                                $entityManager->flush();
                                            }
                                        }else{
                                            $this->addFlash('fail', 'El parentesco del pariente del paciente no puede estar vacio');
                                            return $this->render('familiar/new.html.twig', [
                                                'familiar' => $familiar,
                                                'expediente' => $expediente,
                                                'editar'     => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'el telfono de contacto del pariente del paciente no puede estar vacio');
                                        return $this->render('familiar/new.html.twig', [
                                            'familiar' => $familiar,
                                            'expediente' => $expediente,
                                            'editar'     => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'La fecha de nacimiento del pariente del paciente no puede estar vacio');
                                    return $this->render('familiar/new.html.twig', [
                                        'familiar' => $familiar,
                                        'expediente' => $expediente,
                                        'editar'     => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Los apellidos del pariente del paciente no pueden estar vacios');
                                return $this->render('familiar/new.html.twig', [
                                    'familiar' => $familiar,
                                    'expediente' => $expediente,
                                    'editar'     => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail','Los nombres del pariente del paciente no pueden estar vacios');
                            return $this->render('familiar/new.html.twig', [
                                'familiar' => $familiar,
                                'expediente' => $expediente,
                                'editar'     => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                        $this->addFlash('success', 'Familiar añadido con exito');
                        return $this->redirectToRoute('familiar_index', ['expediente' => $expediente->getId()]);
                    }

                    return $this->render('familiar/new.html.twig', [
                        'familiar' => $familiar,
                        'expediente' => $expediente,
                        'editar'     => $editar,
                        'form' => $form->createView(),
                    ]);    
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            $editar=false;
            $familiar = new Familiar();
            $form = $this->createForm(FamiliarType::class, $familiar);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                if($form["nombres"]->getData() != ""){
                    if($form["apellidos"]->getData() != ""){
                        if($form["fechaNacimiento"]->getData() != ""){
                            if($form["telefono"]->getData() != ""){
                                if($form["descripcion"]->getData() != ""){
                                    if($request->request->get('responsable') != ""){
                                        //CODIGO DE ACCION PARA INGRESAR UN FAMILIAR
                                        $familiar->setNombres($form["nombres"]->getData());
                                        $familiar->setApellidos($form["apellidos"]->getData());
                                        $familiar->setFechaNacimiento($form["fechaNacimiento"]->getData());
                                        $familiar->setTelefono($form["telefono"]->getData());
                                        $familiar->setDescripcion($form["descripcion"]->getData());
                                        $entityManager->persist($familiar);
                                        $familiaresExpediente = new FamiliaresExpediente();
                                        $familiaresExpediente->setExpediente($expediente);
                                        $familiaresExpediente->setFamiliar($familiar);
                                        $familiaresExpediente->setResponsable($request->request->get('responsable'));
                                        $entityManager->persist($familiaresExpediente);
                                        $entityManager->flush();
                                    }else{
                                        $familiar->setNombres($form["nombres"]->getData());
                                        $familiar->setApellidos($form["apellidos"]->getData());
                                        $familiar->setFechaNacimiento($form["fechaNacimiento"]->getData());
                                        $familiar->setTelefono($form["telefono"]->getData());
                                        $familiar->setDescripcion($form["descripcion"]->getData());
                                        $entityManager->persist($familiar);
                                        $familiaresExpediente = new FamiliaresExpediente();
                                        $familiaresExpediente->setExpediente($expediente);
                                        $familiaresExpediente->setFamiliar($familiar);
                                        $familiaresExpediente->setResponsable(false);
                                        $entityManager->persist($familiaresExpediente);
                                        $entityManager->flush();
                                    }
                                }else{
                                    $this->addFlash('fail', 'El parentesco del pariente del paciente no puede estar vacio');
                                    return $this->render('familiar/new.html.twig', [
                                        'familiar' => $familiar,
                                        'expediente' => $expediente,
                                        'editar'     => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'el telfono de contacto del pariente del paciente no puede estar vacio');
                                return $this->render('familiar/new.html.twig', [
                                    'familiar' => $familiar,
                                    'expediente' => $expediente,
                                    'editar'     => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'La fecha de nacimiento del pariente del paciente no puede estar vacio');
                            return $this->render('familiar/new.html.twig', [
                                'familiar' => $familiar,
                                'expediente' => $expediente,
                                'editar'     => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Los apellidos del pariente del paciente no pueden estar vacios');
                        return $this->render('familiar/new.html.twig', [
                            'familiar' => $familiar,
                            'expediente' => $expediente,
                            'editar'     => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail','Los nombres del pariente del paciente no pueden estar vacios');
                    return $this->render('familiar/new.html.twig', [
                        'familiar' => $familiar,
                        'expediente' => $expediente,
                        'editar'     => $editar,
                        'form' => $form->createView(),
                    ]);
                }
                $this->addFlash('success', 'Familiar añadido con exito');
                return $this->redirectToRoute('familiar_index', ['expediente' => $expediente->getId()]);
            }

            return $this->render('familiar/new.html.twig', [
                'familiar' => $familiar,
                'expediente' => $expediente,
                'editar'     => $editar,
                'form' => $form->createView(),
            ]);    
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        
    }

    /**
     * @Route("/{id}/{expediente}", name="familiar_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_FAMILIAR')")
     */
    public function show(Familiar $familiar, Expediente $expediente, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getId() == $expediente->getId() ){
                if($expediente->getHabilitado()){
                    return $this->render('familiar/show.html.twig', [
                        'familiar' => $familiar,
                        'expediente' => $expediente,
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }
        if($expediente->getHabilitado()){
            return $this->render('familiar/show.html.twig', [
                'familiar' => $familiar,
                'expediente' => $expediente,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        
    }

    /**
     * @Route("/{id}/{expediente}/edit", name="familiar_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_FAMILIAR')")
     */
    public function edit(Request $request, Familiar $familiar, Expediente $expediente,Security $AuthUser): Response
    {  
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getId() == $expediente->getId() ){
                if($expediente->getHabilitado()){
                    $editar = true;
                    $familiaresExpediente = $this->getDoctrine()->getRepository(FamiliaresExpediente::class)->find($familiar->getId());
                    $form = $this->createForm(FamiliarType::class, $familiar);
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $familiar->setNombres($form["nombres"]->getData());
                        $familiar->setApellidos($form["apellidos"]->getData());
                        $familiar->setFechaNacimiento($form["fechaNacimiento"]->getData());
                        $familiar->setTelefono($form["telefono"]->getData());
                        $familiar->setDescripcion($form["descripcion"]->getData());
                        $entityManager->persist($familiar);
                        $familiaresExpediente->setExpediente($expediente);
                        $familiaresExpediente->setFamiliar($familiar);
                        $familiaresExpediente->setResponsable($request->request->get('responsable'));
                        $entityManager->persist($familiaresExpediente);
                        $entityManager->flush();
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Familiar modificado con exito');
                        return $this->redirectToRoute('familiar_index', ['expediente' => $expediente->getId()]);
                    }

                    return $this->render('familiar/edit.html.twig', [
                        'familiar' => $familiar,
                        'expediente' => $expediente,
                        'editar'     => $editar,
                        'familiaresExpediente'   => $familiaresExpediente,
                        'form'       => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }


        if($expediente->getHabilitado()){
            $editar = true;
            $familiaresExpediente = $this->getDoctrine()->getRepository(FamiliaresExpediente::class)->find($familiar->getId());
            $form = $this->createForm(FamiliarType::class, $familiar);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $familiar->setNombres($form["nombres"]->getData());
                $familiar->setApellidos($form["apellidos"]->getData());
                $familiar->setFechaNacimiento($form["fechaNacimiento"]->getData());
                $familiar->setTelefono($form["telefono"]->getData());
                $familiar->setDescripcion($form["descripcion"]->getData());
                $entityManager->persist($familiar);
                $familiaresExpediente->setExpediente($expediente);
                $familiaresExpediente->setFamiliar($familiar);
                $familiaresExpediente->setResponsable($request->request->get('responsable'));
                $entityManager->persist($familiaresExpediente);
                $entityManager->flush();
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Familiar modificado con exito');
                return $this->redirectToRoute('familiar_index', ['expediente' => $expediente->getId()]);
            }

            return $this->render('familiar/edit.html.twig', [
                'familiar' => $familiar,
                'expediente' => $expediente,
                'editar'     => $editar,
                'familiaresExpediente'   => $familiaresExpediente,
                'form'       => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        
    }

    /**
     * @Route("/{id}/{expediente}", name="familiar_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_FAMILIAR')")
     */
    public function delete(Request $request, Familiar $familiar, Expediente $expediente, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $expediente->getUsuario()->getClinica()->getId() && $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getId() == $expediente->getId() ){
                if($expediente->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$familiar->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($familiar);
                        $entityManager->remove($this->getDoctrine()->getRepository(FamiliaresExpediente::class)->find($familiar->getId()));
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Familiar eliminado con exito');
                    return $this->redirectToRoute('familiar_index', ['expediente' => $expediente->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('expediente_index');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }
        }


        if($expediente->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$familiar->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($familiar);
                $entityManager->remove($this->getDoctrine()->getRepository(FamiliaresExpediente::class)->find($familiar->getId()));
                $entityManager->flush();
            }
            $this->addFlash('success', 'Familiar eliminado con exito');
            return $this->redirectToRoute('familiar_index', ['expediente' => $expediente->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        
    }
}
