<?php

namespace App\Controller;

use App\Entity\HistorialFamiliar;
use App\Entity\Familiar;
use App\Form\HistorialFamiliarType;
use App\Repository\HistorialFamiliarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/historial/familiar")
 */
class HistorialFamiliarController extends AbstractController
{
    /**
     * @Route("/{familiar}", name="historial_familiar_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_HISTORIAL_FAMILIAR')")
     */
    public function index(HistorialFamiliarRepository $historialFamiliarRepository, Familiar $familiar,Security $AuthUser): Response
    {
        //OBTENIENDO EL ID DEL EXPEDIENTE
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = 'SELECT fex.expediente_id as idExpediente
        FROM familiar as fam, familiares_expediente as fex 
        WHERE fam.id= :idFamiliar AND 
              fam.id=fex.familiar_id
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('idFamiliar' => $familiar->getId()));
        $expediente= $stmt->fetch();

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getUsuario()->getClinica()->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        } 

        if($familiar->getFamiliaresExpedientes()[0]->getExpediente()->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT historial.* FROM historial_familiar as historial, familiar as fam WHERE fam.id=historial.familiar_id AND familiar_id=".$familiar->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->render('historial_familiar/index.html.twig', [
                'historial_familiares' => $result,
                'user'              => $AuthUser,
                'familiar'          => $familiar,
                'expediente'        => $expediente
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/new/{familiar}", name="historial_familiar_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_HISTORIAL_FAMILIAR')")
     */
    public function new(Request $request, Familiar $familiar, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getUsuario()->getClinica()->getId()){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        } 

        if($familiar->getFamiliaresExpedientes()[0]->getExpediente()->getHabilitado()){
            $editar = false;
            $historialFamiliar = new HistorialFamiliar();
            $form = $this->createForm(HistorialFamiliarType::class, $historialFamiliar);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                if($form["descripcion"]->getData() != ""){
                    $historialFamiliar->setDescripcion($form["descripcion"]->getData());
                    $historialFamiliar->setFamiliar($familiar);
                    $entityManager->persist($historialFamiliar);
                    $entityManager->flush();
                    $this->addFlash('success', 'Historial añadido con éxito');
                    return $this->redirectToRoute('historial_familiar_index',['familiar' => $familiar->getId()]);
                }else{
                    $this->addFlash('fail', 'Error, el campo de la descripción no puede ir vacía');
                }
            }
            return $this->render('historial_familiar/new.html.twig', [
                'historial_familiar' => $historialFamiliar,
                'familiar' => $familiar,
                'editar'     => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
        
    }

    /**
     * @Route("/{id}/{familiar}", name="historial_familiar_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_HISTORIAL_FAMILIAR')")
     */
    public function show(HistorialFamiliar $historialFamiliar, Familiar $familiar, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getUsuario()->getClinica()->getId() && $familiar->getFamiliaresExpedientes()[0]->getFamiliar()->getId() == $historialFamiliar->getFamiliar()->getId() ){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        }

        if($familiar->getFamiliaresExpedientes()[0]->getExpediente()->getHabilitado()){
            return $this->render('historial_familiar/show.html.twig', [
                'historial_familiar' => $historialFamiliar,
                'familiar'       => $familiar,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }

    }

    /**
     * @Route("/{id}/{familiar}/edit", name="historial_familiar_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_HISTORIAL_FAMILIAR')")
     */
    public function edit(Request $request, HistorialFamiliar $historialFamiliar, Familiar $familiar): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getUsuario()->getClinica()->getId() && $familiar->getFamiliaresExpedientes()[0]->getFamiliar()->getId() == $historialFamiliar->getFamiliar()->getId() ){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        }

        if($familiar->getFamiliaresExpedientes()[0]->getExpediente()->getHabilitado()){
            $editar = true;
            $form = $this->createForm(HistorialFamiliarType::class, $historialFamiliar);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Historial modificado con éxito');
                return $this->redirectToRoute('historial_familiar_index',['familiar' => $familiar->getId()]);
            }

            return $this->render('historial_familiar/edit.html.twig', [
                'historial_familiar' => $historialFamiliar,
                'familiar'       => $familiar,
                'editar'     => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }

    /**
     * @Route("/{id}/{familiar}", name="historial_familiar_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_HISTORIAL_FAMILIAR')")
     */
    public function delete(Request $request, HistorialFamiliar $historialFamiliar, Familiar $familiar): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $familiar->getFamiliaresExpedientes()[0]->getExpediente()->getUsuario()->getClinica()->getId() && $familiar->getFamiliaresExpedientes()[0]->getFamiliar()->getId() == $historialFamiliar->getFamiliar()->getId() ){
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('expediente_index');
            }  
        }

        if($familiar->getFamiliaresExpedientes()[0]->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$historialFamiliar->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($historialFamiliar);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Historial eliminado con éxito');
            return $this->redirectToRoute('historial_familiar_index',['familiar' => $familiar->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('expediente_index');
        }
    }
}




