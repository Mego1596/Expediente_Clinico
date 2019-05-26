<?php

namespace App\Controller;

use App\Entity\PlanTratamiento;
use App\Entity\HistoriaMedica;
use App\Form\PlanTratamientoType;
use App\Repository\PlanTratamientoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/plan/tratamiento")
 */
class PlanTratamientoController extends AbstractController
{
    /**
     * @Route("/{historiaMedica}", name="plan_tratamiento_index", methods={"GET"})
     */
    public function index(PlanTratamientoRepository $planTratamientoRepository, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($historiaMedica->getCita()->getExpediente()->getHabilitado()){
                    return $this->render('plan_tratamiento/index.html.twig', [
                        'plan_tratamientos' => $planTratamientoRepository->findAll(),
                        'user'  =>$AuthUser,
                        'historia_medica'   => $historiaMedica,
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
        return $this->render('plan_tratamiento/index.html.twig', [
            'plan_tratamientos' => $planTratamientoRepository->findAll(),
            'user'  =>$AuthUser,
            'historia_medica'   => $historiaMedica,
        ]);
    }

    /**
     * @Route("/new/{historiaMedica}", name="plan_tratamiento_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                //DESDE AQUI 
                if($historiaMedica->getCita()->getExpediente()->getHabilitado()){
                    $editar = false;
                    $planTratamiento = new PlanTratamiento();
                    $form = $this->createForm(PlanTratamientoType::class, $planTratamiento);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        if($form["medicamento"]->getData() != ""){
                            if($form["dosis"]->getData() != ""){
                                if($form["frecuencia"]->getData() != ""){
                                    if($form["tipoMedicamento"]->getData() != ""){
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $planTratamiento->setHistoriaMedica($historiaMedica);
                                        $entityManager->persist($planTratamiento);
                                        $entityManager->flush();
                                        $this->addFlash('success', 'Medicamento añadido con exito');
                                        return $this->redirectToRoute('plan_tratamiento_index', ['historiaMedica' => $historiaMedica->getId()]);
                                    }else{
                                        $this->addFlash('fail','Error, el tipo de medicamento asignado no puede ir vacio');
                                        return $this->render('plan_tratamiento/new.html.twig', [
                                            'plan_tratamiento' => $planTratamiento,
                                            'historia_medica'  => $historiaMedica,
                                            'editar'           => $editar,
                                            'form' => $form->createView(),
                                        ]);

                                    }
                                }else{
                                    $this->addFlash('fail','Error,la frecuencia del consumo del medicamento no puede ir vacia');
                                    return $this->render('plan_tratamiento/new.html.twig', [
                                        'plan_tratamiento' => $planTratamiento,
                                        'historia_medica'  => $historiaMedica,
                                        'editar'           => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail','Error, la dosis del medicamento no puede ir vacia');
                                return $this->render('plan_tratamiento/new.html.twig', [
                                    'plan_tratamiento' => $planTratamiento,
                                    'historia_medica'  => $historiaMedica,
                                    'editar'           => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail','Error,asigne un medicamento no puede ir vacio');
                            return $this->render('plan_tratamiento/new.html.twig', [
                                'plan_tratamiento' => $planTratamiento,
                                'historia_medica'  => $historiaMedica,
                                'editar'           => $editar,
                                'form' => $form->createView(),
                            ]);
                        }  
                    }
                    return $this->render('plan_tratamiento/new.html.twig', [
                        'plan_tratamiento' => $planTratamiento,
                        'historia_medica'  => $historiaMedica,
                        'editar'           => $editar,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
                //HASTA AQUI
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        } 

        if($historiaMedica->getCita()->getExpediente()->getHabilitado()){
            $editar = false;
            $planTratamiento = new PlanTratamiento();
            $form = $this->createForm(PlanTratamientoType::class, $planTratamiento);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if($form["medicamento"]->getData() != ""){
                    if($form["dosis"]->getData() != ""){
                        if($form["frecuencia"]->getData() != ""){
                            if($form["tipoMedicamento"]->getData() != ""){
                                $entityManager = $this->getDoctrine()->getManager();
                                $planTratamiento->setHistoriaMedica($historiaMedica);
                                $entityManager->persist($planTratamiento);
                                $entityManager->flush();
                                $this->addFlash('success', 'Medicamento añadido con exito');
                                return $this->redirectToRoute('plan_tratamiento_index', ['historiaMedica' => $historiaMedica->getId()]);
                            }else{
                                $this->addFlash('fail','Error, el tipo de medicamento asignado no puede ir vacio');
                                return $this->render('plan_tratamiento/new.html.twig', [
                                    'plan_tratamiento' => $planTratamiento,
                                    'historia_medica'  => $historiaMedica,
                                    'editar'           => $editar,
                                    'form' => $form->createView(),
                                ]);

                            }
                        }else{
                            $this->addFlash('fail','Error,la frecuencia del consumo del medicamento no puede ir vacia');
                            return $this->render('plan_tratamiento/new.html.twig', [
                                'plan_tratamiento' => $planTratamiento,
                                'historia_medica'  => $historiaMedica,
                                'editar'           => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail','Error, la dosis del medicamento no puede ir vacia');
                        return $this->render('plan_tratamiento/new.html.twig', [
                            'plan_tratamiento' => $planTratamiento,
                            'historia_medica'  => $historiaMedica,
                            'editar'           => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail','Error, asigne un medicamento no puede ir vacio');
                    return $this->render('plan_tratamiento/new.html.twig', [
                        'plan_tratamiento' => $planTratamiento,
                        'historia_medica'  => $historiaMedica,
                        'editar'           => $editar,
                        'form' => $form->createView(),
                    ]);
                }   
            }

            return $this->render('plan_tratamiento/new.html.twig', [
                'plan_tratamiento' => $planTratamiento,
                'historia_medica'  => $historiaMedica,
                'editar'           => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/{id}/{historiaMedica}/edit", name="plan_tratamiento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PlanTratamiento $planTratamiento, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $planTratamiento->getHistoriaMedica()->getId() == $historiaMedica->getId()){
                if($historiaMedica->getCita()->getExpediente()->getHabilitado()){

                    //DESDE AQUI

                    $editar=true;
                    $form = $this->createForm(PlanTratamientoType::class, $planTratamiento);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Medicamento modificado con exito');
                        return $this->redirectToRoute('plan_tratamiento_index', [
                            'historiaMedica'    => $historiaMedica->getId(),
                        ]);
                    }

                    return $this->render('plan_tratamiento/edit.html.twig', [
                        'plan_tratamiento' => $planTratamiento,
                        'editar'           => $editar,
                        'historia_medica'  => $historiaMedica,
                        'form' => $form->createView(),
                    ]);

                    //HASTA AQUI
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        } 


        $editar=true;
        $form = $this->createForm(PlanTratamientoType::class, $planTratamiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Medicamento modificado con exito');
            return $this->redirectToRoute('plan_tratamiento_index', [
                'historiaMedica'    => $historiaMedica->getId(),
            ]);
        }

        return $this->render('plan_tratamiento/edit.html.twig', [
            'plan_tratamiento' => $planTratamiento,
            'editar'           => $editar,
            'historia_medica'  => $historiaMedica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{historiaMedica}", name="plan_tratamiento_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PlanTratamiento $planTratamiento, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $planTratamiento->getHistoriaMedica()->getId() == $historiaMedica->getId()){
                if($historiaMedica->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$planTratamiento->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($planTratamiento);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Medicamento eliminado con exito');
                    return $this->redirectToRoute('plan_tratamiento_index',['historiaMedica' => $historiaMedica->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        } 

        if($historiaMedica->getCita()->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$planTratamiento->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($planTratamiento);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Medicamento eliminado con exito');
            return $this->redirectToRoute('plan_tratamiento_index',['historiaMedica' => $historiaMedica->getId()]);
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}