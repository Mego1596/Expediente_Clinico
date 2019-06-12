<?php

namespace App\Controller;

use App\Entity\Sala;
use App\Entity\Clinica;
use App\Form\SalaType;
use App\Repository\SalaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
/**
 * @Route("/sala")
 */
class SalaController extends AbstractController
{
    /**
     * @Route("/{clinica}", name="sala_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_SALA')")
     */
    public function index(SalaRepository $salaRepository,Clinica $clinica,Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId()){
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY = "SELECT * FROM sala WHERE clinica_id = ".$clinica->getId().";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                return $this->render('sala/index.html.twig', [
                    'salas' => $result,
                    'clinica' => $clinica,
                    'user'  => $AuthUser,
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('sala_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }
        $em = $this->getDoctrine()->getManager();
                $RAW_QUERY = "SELECT * FROM sala WHERE clinica_id = ".$clinica->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('sala/index.html.twig', [
            'salas' => $result,
            'clinica' => $clinica,
            'user'  => $AuthUser,
        ]);
    }

    /**
     * @Route("/new/{clinica}", name="sala_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_SALA')")
     */
    public function new(Request $request,Clinica $clinica,Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId()){
                $editar=false;
                $sala = new Sala();
                $form = $this->createForm(SalaType::class, $sala);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    if($form["nombreSala"]->getData() != ""){
                        $sala->setClinica($clinica);
                        $entityManager->persist($sala);
                        $entityManager->flush();
                    }else{
                        $this->addFlash('fail', 'Error, el nombre de la sala no puede estar vacío');
                        return $this->render('sala/new.html.twig', [
                            'sala' => $sala,
                            'editar' =>$editar,
                            'clinica'=>$clinica,
                            'form' => $form->createView(),
                        ]);
                    }
                    
                    $this->addFlash('success','Sala añadida con éxito');
                    return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
                }

                return $this->render('sala/new.html.twig', [
                    'sala' => $sala,
                    'editar' =>$editar,
                    'clinica'=>$clinica,
                    'form' => $form->createView(),
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('sala_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }

        $editar=false;
        $sala = new Sala();
        $form = $this->createForm(SalaType::class, $sala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($form["nombreSala"]->getData() != ""){
                $sala->setClinica($clinica);
                $entityManager->persist($sala);
                $entityManager->flush();
            }else{
                $this->addFlash('fail', 'Error, el nombre de la sala no puede estar vacío');
                return $this->render('sala/new.html.twig', [
                    'sala' => $sala,
                    'editar' =>$editar,
                    'clinica'=>$clinica,
                    'form' => $form->createView(),
                ]);
            }
            
            $this->addFlash('success','Sala añadida con éxito');
            return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
        }

        return $this->render('sala/new.html.twig', [
            'sala' => $sala,
            'editar' =>$editar,
            'clinica'=>$clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{clinica}", name="sala_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_SALA')")
     */
    public function show(Sala $sala,Clinica $clinica, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId() && $AuthUser->getUser()->getClinica()->getId() == $sala->getClinica()->getId() ){
                return $this->render('sala/show.html.twig', [
                    'sala' => $sala,
                    'clinica'=>$clinica,
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('sala_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }
        return $this->render('sala/show.html.twig', [
            'sala' => $sala,
            'clinica'=>$clinica,
        ]);
    }

    /**
     * @Route("/{id}/{clinica}/edit/", name="sala_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_SALA')")
     */
    public function edit(Request $request, Sala $sala,Clinica $clinica, Security $AuthUser): Response
    {

        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId() && $AuthUser->getUser()->getClinica()->getId() == $sala->getClinica()->getId()){
                $editar=true;
                $form = $this->createForm(SalaType::class, $sala);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('success','Sala modificada con éxito');
                    return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
                }
                return $this->render('sala/edit.html.twig', [
                    'sala' => $sala,
                    'editar' =>$editar,
                    'clinica'=>$clinica,
                    'form' => $form->createView(),
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('sala_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
            }
        }

        $editar=true;
        $form = $this->createForm(SalaType::class, $sala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Sala modificada con éxito');
            return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
        }


        return $this->render('sala/edit.html.twig', [
            'sala' => $sala,
            'editar' =>$editar,
            'clinica'=>$clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{clinica}", name="sala_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_SALA')")
     */
    public function delete(Request $request, Sala $sala,Clinica $clinica,Security $AuthUser): Response
    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT COUNT(*) as cuenta FROM habitacion as h, sala as s WHERE 
        h.sala_id = s.id AND
        s.id = ".$sala->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $validacionBloqueoEliminar = $statement->fetchAll();
        if((int) $validacionBloqueoEliminar[0]["cuenta"] >= 1){
            $this->addFlash('notice','Para borrar la sala verifique que este no tenga habitaciones asociadas a el');
            if($AuthUser->getUser()->getRol()->getNombreRol() == 'ROLE_SA'){
                return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
            }else{
                return $this->redirectToRoute('sala_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));  
            }
            
        }else{
            //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
            if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
                if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId() && $AuthUser->getUser()->getClinica()->getId() == $sala->getClinica()->getId() ){
                    if ($this->isCsrfTokenValid('delete'.$sala->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($sala);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Sala eliminada con exito');
                    return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
                }else{
                    $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                    return $this->redirectToRoute('sala_index',array('clinica'=>$AuthUser->getUser()->getClinica()->getId()));
                }
            }
            if ($this->isCsrfTokenValid('delete'.$sala->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($sala);
                $entityManager->flush();
            }
        }
        $this->addFlash('success', 'Sala eliminada con exito');
        return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
    }
}
