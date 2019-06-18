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
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_PLAN_TRATAMIENTO')")
     */
    public function index(PlanTratamientoRepository $planTratamientoRepository, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        } 
        
        if($historiaMedica->getCita()->getExpediente()->getHabilitado()){
            return $this->render('plan_tratamiento/index.html.twig', [
                'plan_tratamientos' => $planTratamientoRepository->findBy(['historiaMedica' => $historiaMedica->getId()]),
                'user'  =>$AuthUser,
                'historia_medica'   => $historiaMedica,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/new/{historiaMedica}", name="plan_tratamiento_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_PLAN_TRATAMIENTO')")
     */
    public function new(Request $request, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
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
                                $this->addFlash('success', 'Medicamento añadido con éxito');
                                return $this->redirectToRoute('plan_tratamiento_index', ['historiaMedica' => $historiaMedica->getId()]);
                            }else{
                                $this->addFlash('fail','Error, el tipo de medicamento asignado no puede ir vacío');
                            }
                        }else{
                            $this->addFlash('fail','Error,la frecuencia del consumo del medicamento no puede ir vacía');
                        }
                    }else{
                        $this->addFlash('fail','Error, la dosis del medicamento no puede ir vacía');
                    }
                }else{
                    $this->addFlash('fail','Error, asigne un medicamento no puede ir vacío');
                }   
            }

            return $this->render('plan_tratamiento/new.html.twig', [
                'plan_tratamiento' => $planTratamiento,
                'historia_medica'  => $historiaMedica,
                'editar'           => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/{id}/{historiaMedica}/edit", name="plan_tratamiento_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_PLAN_TRATAMIENTO')")
     */
    public function edit(Request $request, PlanTratamiento $planTratamiento, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $planTratamiento->getHistoriaMedica()->getId() == $historiaMedica->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        } 

        if($historiaMedica->getCita()->getExpediente()->getHabilitado()){
            $editar=true;
            $form = $this->createForm(PlanTratamientoType::class, $planTratamiento);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Medicamento modificado con éxito');
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
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/{historiaMedica}", name="plan_tratamiento_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_PLAN_TRATAMIENTO')")
     */
    public function delete(Request $request, PlanTratamiento $planTratamiento, Security $AuthUser, HistoriaMedica $historiaMedica): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $historiaMedica->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $planTratamiento->getHistoriaMedica()->getId() == $historiaMedica->getId()){
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
            $this->addFlash('success', 'Medicamento eliminado con éxito');
            return $this->redirectToRoute('plan_tratamiento_index',['historiaMedica' => $historiaMedica->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
