<?php

namespace App\Controller;

use App\Entity\ExamenSolicitado;
use App\Entity\Cita;
use App\Entity\Clinica;
use App\Entity\User;
use App\Entity\Expediente;
use App\Form\ExamenSolicitadoType;
use App\Repository\ExamenSolicitadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/solicitado")
 */
class ExamenSolicitadoController extends AbstractController
{
    /**
     * @Route("/{cita}", name="examen_solicitado_index", methods={"GET"})
     */
    public function index(ExamenSolicitadoRepository $examenSolicitadoRepository, Cita $cita, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId()){
                if($cita->getExpediente()->getHabilitado()){
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE cita_id =".$cita->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    return $this->render('examen_solicitado/index.html.twig', [
                        'examen_solicitados' => $result,
                        'cantidad'       => count($result),
                        'user'           => $AuthUser,
                        'cita'           => $cita,
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
        

        if($cita->getExpediente()->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE cita_id =".$cita->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $this->render('examen_solicitado/index.html.twig', [
                'examen_solicitados' => $result,
                'cantidad'       => count($result),
                'user'           => $AuthUser,
                'cita'           => $cita,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/new/{cita}", name="examen_solicitado_new", methods={"GET","POST"})
     */
    public function new(Request $request, Cita $cita, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId()){
                if($cita->getExpediente()->getHabilitado()){
                    $editar=false;
                    $examenSolicitado = new ExamenSolicitado();
                    $form = $this->createForm(ExamenSolicitadoType::class, $examenSolicitado);
                    $form->handleRequest($request);

                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE cita_id =".$cita->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if(count($result) < 9 ){
                        if ($form->isSubmitted() && $form->isValid()) {
                            if($form["tipoExamen"]->getData() != ""){
                                if(($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 3) || ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 4) ){
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $examenSolicitado->setTipoExamen("Orina");
                                    if($form["categoria"]->getData() == 1){
                                        $examenSolicitado->setCategoria("Quimico");
                                        $em = $this->getDoctrine()->getManager();
                                        $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Quimico' AND cita_id =".$cita->getId().";";
                                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                                        $statement->execute();
                                        $result = $statement->fetchAll();
                                        if($result == null){
                                            $examenSolicitado->setCita($cita);
                                            $entityManager->persist($examenSolicitado);
                                            $entityManager->flush();
                                        }else{
                                            $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                            return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                        }
                                    }
                                    if($form["categoria"]->getData() == 2){
                                        $examenSolicitado->setCategoria("Microscopico");
                                        $em = $this->getDoctrine()->getManager();
                                        $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Microscopico' AND cita_id =".$cita->getId().";";
                                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                                        $statement->execute();
                                        $result = $statement->fetchAll();
                                        if($result == null){
                                            $examenSolicitado->setCita($cita);
                                            $entityManager->persist($examenSolicitado);
                                            $entityManager->flush();
                                        }else{
                                            $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                            return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                        } 
                                    }
                                    if($form["categoria"]->getData() == 3){
                                        $examenSolicitado->setCategoria("Macroscopico");
                                        $em = $this->getDoctrine()->getManager();
                                        $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Macroscopico' AND cita_id =".$cita->getId().";";
                                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                                        $statement->execute();
                                        $result = $statement->fetchAll();
                                        if($result == null){
                                            $examenSolicitado->setCita($cita);
                                            $entityManager->persist($examenSolicitado);
                                            $entityManager->flush();
                                        }else{
                                            $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                            return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                        }
                                    }
                                    if($form["categoria"]->getData() == 4){
                                        $examenSolicitado->setCategoria("Cristaluria");
                                        $em = $this->getDoctrine()->getManager();
                                        $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Cristaluria' AND cita_id =".$cita->getId().";";
                                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                                        $statement->execute();
                                        $result = $statement->fetchAll();
                                        if($result == null){
                                            $examenSolicitado->setCita($cita);
                                            $entityManager->persist($examenSolicitado);
                                            $entityManager->flush();
                                        }else{
                                            $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                            return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                        }
                                    }
                                    $this->addFlash('success', 'Examen añadido con exito');
                                    return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);

                                }elseif(($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == 3) ){
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $examenSolicitado->setTipoExamen("Heces");
                                        if($form["categoria"]->getData() == 1){
                                            $examenSolicitado->setCategoria("Quimico");
                                            $em = $this->getDoctrine()->getManager();
                                            $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Heces' AND categoria ='Quimico' AND cita_id =".$cita->getId().";";
                                            $statement = $em->getConnection()->prepare($RAW_QUERY);
                                            $statement->execute();
                                            $result = $statement->fetchAll();
                                            if($result == null){
                                                $examenSolicitado->setCita($cita);
                                                $entityManager->persist($examenSolicitado);
                                                $entityManager->flush();
                                            }else{
                                                $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                            }
                                        }
                                        if($form["categoria"]->getData() == 2){
                                            $examenSolicitado->setCategoria("Microscopico");  
                                            $em = $this->getDoctrine()->getManager();
                                            $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Heces' AND categoria ='Microscopico' AND cita_id =".$cita->getId().";";
                                            $statement = $em->getConnection()->prepare($RAW_QUERY);
                                            $statement->execute();
                                            $result = $statement->fetchAll();
                                            if($result == null){
                                                $examenSolicitado->setCita($cita);
                                                $entityManager->persist($examenSolicitado);
                                                $entityManager->flush();
                                            }else{
                                                $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                            } 
                                        }
                                        if($form["categoria"]->getData() == 3){
                                            $examenSolicitado->setCategoria("Macroscopico");
                                            $em = $this->getDoctrine()->getManager();
                                            $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Heces' AND categoria ='Macroscopico' AND cita_id =".$cita->getId().";";
                                            $statement = $em->getConnection()->prepare($RAW_QUERY);
                                            $statement->execute();
                                            $result = $statement->fetchAll();
                                            if($result == null){
                                                $examenSolicitado->setCita($cita);
                                                $entityManager->persist($examenSolicitado);
                                                $entityManager->flush();
                                            }else{
                                                $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                            }
                                        }
                                        $examenSolicitado->setCita($cita);
                                        $entityManager->persist($examenSolicitado);
                                        $entityManager->flush();
                                        $this->addFlash('success', 'Examen añadido con exito');
                                        return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                                }elseif( ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == "" ) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == "" ) ){
                                        $entityManager = $this->getDoctrine()->getManager();
                                        $examenSolicitado->setCita($cita);
                                        $examenSolicitado->setCategoria("-");
                                        if($form["tipoExamen"]->getData() == 3){
                                            $examenSolicitado->setTipoExamen("Quimica Sanguinea");
                                            $em = $this->getDoctrine()->getManager();
                                            $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Quimica Sanguinea' AND categoria ='-' AND cita_id =".$cita->getId().";";
                                            $statement = $em->getConnection()->prepare($RAW_QUERY);
                                            $statement->execute();
                                            $result = $statement->fetchAll();
                                            if($result == null){
                                                $examenSolicitado->setCita($cita);
                                                $entityManager->persist($examenSolicitado);
                                                $entityManager->flush();
                                            }else{
                                                $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                            } 

                                        }else{
                                            $examenSolicitado->setTipoExamen("Hematologico");
                                            $em = $this->getDoctrine()->getManager();
                                            $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Hematologico' AND categoria ='-' AND cita_id =".$cita->getId().";";
                                            $statement = $em->getConnection()->prepare($RAW_QUERY);
                                            $statement->execute();
                                            $result = $statement->fetchAll();
                                            if($result == null){
                                                $examenSolicitado->setCita($cita);
                                                $entityManager->persist($examenSolicitado);
                                                $entityManager->flush();
                                            }else{
                                                $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                            }
                                        }
                                        $this->addFlash('success', 'Examen añadido con exito');
                                        return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                                }else{
                                    if(($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 3) || ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 4) ){
                                        $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    }elseif(($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 3) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 4)  ){
                                        $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    }elseif( ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == "" ) || ($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == "" ) ){
                                        $this->addFlash('fail', 'Error este tipo de examen posee categoria no puede ir vacia por favor seleccione una categoria.');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    }else{
                                        $this->addFlash('fail', 'Error este tipo de examen no posee examen de cristaluria');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    }
                                }
                            }else{
                                $this->addFlash('fail', 'Error, el tipo de examen no puede ir vacio');
                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                            }
                        }
                    }else{
                        $this->addFlash('fail', 'Error, ya ha creado todos los examenes posibles que se pueden solicitar por cita, por favor verifique el examen existente si desea hacer modificaciones');
                        return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                    }

                    return $this->render('examen_solicitado/new.html.twig', [
                        'examen_solicitado' => $examenSolicitado,
                        'cita' => $cita,
                        'editar' => $editar,
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


        if($cita->getExpediente()->getHabilitado()){
            $editar=false;
            $examenSolicitado = new ExamenSolicitado();
            $form = $this->createForm(ExamenSolicitadoType::class, $examenSolicitado);
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE cita_id =".$cita->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if(count($result) < 9 ){
                if ($form->isSubmitted() && $form->isValid()) {
                    if($form["tipoExamen"]->getData() != ""){
                        if(($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 3) || ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == 4) ){
                            $entityManager = $this->getDoctrine()->getManager();
                            $examenSolicitado->setTipoExamen("Orina");
                            if($form["categoria"]->getData() == 1){
                                $examenSolicitado->setCategoria("Quimico");
                                $em = $this->getDoctrine()->getManager();
                                $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Quimico' AND cita_id =".$cita->getId().";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                if($result == null){
                                    $examenSolicitado->setCita($cita);
                                    $entityManager->persist($examenSolicitado);
                                    $entityManager->flush();
                                }else{
                                    $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                    return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                }
                            }
                            if($form["categoria"]->getData() == 2){
                                $examenSolicitado->setCategoria("Microscopico");
                                $em = $this->getDoctrine()->getManager();
                                $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Microscopico' AND cita_id =".$cita->getId().";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                if($result == null){
                                    $examenSolicitado->setCita($cita);
                                    $entityManager->persist($examenSolicitado);
                                    $entityManager->flush();
                                }else{
                                    $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                    return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                } 
                            }
                            if($form["categoria"]->getData() == 3){
                                $examenSolicitado->setCategoria("Macroscopico");
                                $em = $this->getDoctrine()->getManager();
                                $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Macroscopico' AND cita_id =".$cita->getId().";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                if($result == null){
                                    $examenSolicitado->setCita($cita);
                                    $entityManager->persist($examenSolicitado);
                                    $entityManager->flush();
                                }else{
                                    $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                    return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                }
                            }
                            if($form["categoria"]->getData() == 4){
                                $examenSolicitado->setCategoria("Cristaluria");
                                $em = $this->getDoctrine()->getManager();
                                $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Orina' AND categoria ='Cristaluria' AND cita_id =".$cita->getId().";";
                                $statement = $em->getConnection()->prepare($RAW_QUERY);
                                $statement->execute();
                                $result = $statement->fetchAll();
                                if($result == null){
                                    $examenSolicitado->setCita($cita);
                                    $entityManager->persist($examenSolicitado);
                                    $entityManager->flush();
                                }else{
                                    $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                    return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                }
                            }
                            $this->addFlash('success', 'Examen añadido con exito');
                            return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);

                        }elseif(($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == 3) ){
                                $entityManager = $this->getDoctrine()->getManager();
                                $examenSolicitado->setTipoExamen("Heces");
                                if($form["categoria"]->getData() == 1){
                                    $examenSolicitado->setCategoria("Quimico");
                                    $em = $this->getDoctrine()->getManager();
                                    $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Heces' AND categoria ='Quimico' AND cita_id =".$cita->getId().";";
                                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                                    $statement->execute();
                                    $result = $statement->fetchAll();
                                    if($result == null){
                                        $examenSolicitado->setCita($cita);
                                        $entityManager->persist($examenSolicitado);
                                        $entityManager->flush();
                                    }else{
                                        $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    }
                                }
                                if($form["categoria"]->getData() == 2){
                                    $examenSolicitado->setCategoria("Microscopico");  
                                    $em = $this->getDoctrine()->getManager();
                                    $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Heces' AND categoria ='Microscopico' AND cita_id =".$cita->getId().";";
                                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                                    $statement->execute();
                                    $result = $statement->fetchAll();
                                    if($result == null){
                                        $examenSolicitado->setCita($cita);
                                        $entityManager->persist($examenSolicitado);
                                        $entityManager->flush();
                                    }else{
                                        $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    } 
                                }
                                if($form["categoria"]->getData() == 3){
                                    $examenSolicitado->setCategoria("Macroscopico");
                                    $em = $this->getDoctrine()->getManager();
                                    $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Heces' AND categoria ='Macroscopico' AND cita_id =".$cita->getId().";";
                                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                                    $statement->execute();
                                    $result = $statement->fetchAll();
                                    if($result == null){
                                        $examenSolicitado->setCita($cita);
                                        $entityManager->persist($examenSolicitado);
                                        $entityManager->flush();
                                    }else{
                                        $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    }
                                }
                                $examenSolicitado->setCita($cita);
                                $entityManager->persist($examenSolicitado);
                                $entityManager->flush();
                                $this->addFlash('success', 'Examen añadido con exito');
                                return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                        }elseif( ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == "" ) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == "" ) ){
                                $entityManager = $this->getDoctrine()->getManager();
                                $examenSolicitado->setCita($cita);
                                $examenSolicitado->setCategoria("-");
                                if($form["tipoExamen"]->getData() == 3){
                                    $examenSolicitado->setTipoExamen("Quimica Sanguinea");
                                    $em = $this->getDoctrine()->getManager();
                                    $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Quimica Sanguinea' AND categoria ='-' AND cita_id =".$cita->getId().";";
                                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                                    $statement->execute();
                                    $result = $statement->fetchAll();
                                    if($result == null){
                                        $examenSolicitado->setCita($cita);
                                        $entityManager->persist($examenSolicitado);
                                        $entityManager->flush();
                                    }else{
                                        $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    } 

                                }else{
                                    $examenSolicitado->setTipoExamen("Hematologico");
                                    $em = $this->getDoctrine()->getManager();
                                    $RAW_QUERY = "SELECT examen.* FROM `examen_solicitado` as examen WHERE tipo_examen= 'Hematologico' AND categoria ='-' AND cita_id =".$cita->getId().";";
                                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                                    $statement->execute();
                                    $result = $statement->fetchAll();
                                    if($result == null){
                                        $examenSolicitado->setCita($cita);
                                        $entityManager->persist($examenSolicitado);
                                        $entityManager->flush();
                                    }else{
                                        $this->addFlash('fail', 'Error, ya se ha registrado este tipo de examen, vuelva a intentarlo con otro tipo de examen');
                                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                                    }
                                }
                                $this->addFlash('success', 'Examen añadido con exito');
                                return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                        }else{
                            if(($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 3) || ($form["tipoExamen"]->getData() == 3 && $form["categoria"]->getData() == 4) ){
                                $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                            }elseif(($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 1) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 2) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 3) || ($form["tipoExamen"]->getData() == 4 && $form["categoria"]->getData() == 4)  ){
                                $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                            }elseif( ($form["tipoExamen"]->getData() == 1 && $form["categoria"]->getData() == "" ) || ($form["tipoExamen"]->getData() == 2 && $form["categoria"]->getData() == "" ) ){
                                $this->addFlash('fail', 'Error este tipo de examen posee categoria no puede ir vacia por favor seleccione una categoria.');
                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                            }else{
                                $this->addFlash('fail', 'Error este tipo de examen no posee examen de cristaluria');
                                return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                            }
                        }
                    }else{
                        $this->addFlash('fail', 'Error, el tipo de examen no puede ir vacio');
                        return $this->redirectToRoute('examen_solicitado_new',['cita' => $cita->getId()]);   
                    }
                }
            }else{
                $this->addFlash('fail', 'Error, ya ha creado todos los examenes posibles que se pueden solicitar por cita, por favor verifique el examen existente si desea hacer modificaciones');
                return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
            }

            return $this->render('examen_solicitado/new.html.twig', [
                'examen_solicitado' => $examenSolicitado,
                'cita' => $cita,
                'editar' => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        } 

    }

    /**
     * @Route("/{id}/{cita}", name="examen_solicitado_show", methods={"GET"})
     */
    /*
    public function show(ExamenSolicitado $examenSolicitado, Cita $cita, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId()){
                if($cita->getExpediente()->getHabilitado()){
                    return $this->render('examen_solicitado/show.html.twig', [
                        'examen_solicitado' => $examenSolicitado,
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
        
    }
    */

    /**
     * @Route("/{id}/{cita}/edit", name="examen_solicitado_edit", methods={"GET","POST"})
     */
    /*
    public function edit(Request $request, ExamenSolicitado $examenSolicitado, Cita $cita, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId()){
                if($cita->getExpediente()->getHabilitado()){
                    $editar=true;
                    $form = $this->createFormBuilder( $examenSolicitado)
                    ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
                    ->getForm();
                    $form->handleRequest($request);
                    if ($form->isSubmitted() && $form->isValid()) {
                        if($request->request->get('examen_solicitado_tipoExamen') != ""){
                            if(($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 1) || ($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 3) || ($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 4) ){
                                $entityManager = $this->getDoctrine()->getManager();
                                $examenSolicitado->setTipoExamen("Orina");
                                if($request->request->get('examen_solicitado_categoria') == 1){
                                    $examenSolicitado->setCategoria("Quimico");
                                }
                                if($request->request->get('examen_solicitado_categoria') == 2){
                                    $examenSolicitado->setCategoria("Microscopico");   
                                }
                                if($request->request->get('examen_solicitado_categoria') == 3){
                                    $examenSolicitado->setCategoria("Macroscopico");
                                }
                                if($request->request->get('examen_solicitado_categoria') == 4){
                                    $examenSolicitado->setCategoria("Cristaluria");
                                }
                                $entityManager->persist($examenSolicitado);
                                $entityManager->flush();
                                $this->addFlash('success', 'Examen añadido con exito');
                                return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);

                            }elseif(($request->request->get('examen_solicitado_tipoExamen') == 2 && $request->request->get('examen_solicitado_categoria') || ($request->request->get('examen_solicitado_tipoExamen') == 2 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 2 && $request->request->get('examen_solicitado_categoria') == 3) )){
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $examenSolicitado->setTipoExamen("Heces");
                                    if($request->request->get('examen_solicitado_categoria') == 1){
                                    $examenSolicitado->setCategoria("Quimico");
                                    }
                                    if($request->request->get('examen_solicitado_categoria') == 2){
                                        $examenSolicitado->setCategoria("Microscopico");   
                                    }
                                    if($request->request->get('examen_solicitado_categoria') == 3){
                                        $examenSolicitado->setCategoria("Macroscopico");
                                    }
                                    $entityManager->persist($examenSolicitado);
                                    $entityManager->flush();
                                    $this->addFlash('success', 'Examen añadido con exito');
                                    return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                            }elseif( ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == "" ) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == "" ) ){
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $examenSolicitado->setCategoria("-");
                                    if($request->request->get('examen_solicitado_tipoExamen') == 3){
                                        $examenSolicitado->setTipoExamen("Quimica Sanguinea");
                                    }else{
                                        $examenSolicitado->setTipoExamen("Hematologico");
                                    }
                                    $entityManager->persist($examenSolicitado);
                                    $entityManager->flush();
                                    $this->addFlash('success', 'Examen modificado con exito');
                                    return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                            }else{
                                if(($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 1) || ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 3) || ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 4) ){
                                    $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                                    return $this->render('examen_solicitado/edit.html.twig', [
                                        'examen_solicitado' => $examenSolicitado,
                                        'cita' => $cita,
                                        'editar' => $editar,
                                        'form' => $form->createView(),
                                    ]);   
                                }elseif(($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 1) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 3) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 4)  ){
                                    $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                                    return $this->render('examen_solicitado/edit.html.twig', [
                                        'examen_solicitado' => $examenSolicitado,
                                        'cita' => $cita,
                                        'editar' => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }elseif( ($request->request->get('examen_solicitado_tipoExamen') == 1 && $form["categoria"]->getData() == "" ) || ($request->request->get('examen_solicitado_tipoExamen') == 2 && $form["categoria"]->getData() == "" ) ){
                                    $this->addFlash('fail', 'Error este tipo de examen posee categoria no puede ir vacia por favor seleccione una categoria.');
                                    return $this->render('examen_solicitado/edit.html.twig', [
                                        'examen_solicitado' => $examenSolicitado,
                                        'cita' => $cita,
                                        'editar' => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }else{
                                    $this->addFlash('fail', 'Error este tipo de examen no posee examen de cristaluria');
                                    return $this->render('examen_solicitado/edit.html.twig', [
                                        'examen_solicitado' => $examenSolicitado,
                                        'cita' => $cita,
                                        'editar' => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }
                            
                        }else{
                            $this->addFlash('fail', 'Error, el tipo de examen no puede ir vacio');
                            return $this->render('examen_solicitado/edit.html.twig', [
                                'examen_solicitado' => $examenSolicitado,
                                'cita' => $cita,
                                'editar' => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }

                    return $this->render('examen_solicitado/edit.html.twig', [
                        'examen_solicitado' => $examenSolicitado,
                        'cita' => $cita,
                        'editar' => $editar,
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

        if($cita->getExpediente()->getHabilitado()){
            $editar=true;
            $form = $this->createFormBuilder( $examenSolicitado)
            ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
            ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if($request->request->get('examen_solicitado_tipoExamen') != ""){
                    if(($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 1) || ($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 3) || ($request->request->get('examen_solicitado_tipoExamen') == 1 && $request->request->get('examen_solicitado_categoria') == 4) ){
                        $entityManager = $this->getDoctrine()->getManager();
                        $examenSolicitado->setTipoExamen("Orina");
                        if($request->request->get('examen_solicitado_categoria') == 1){
                            $examenSolicitado->setCategoria("Quimico");
                        }
                        if($request->request->get('examen_solicitado_categoria') == 2){
                            $examenSolicitado->setCategoria("Microscopico");   
                        }
                        if($request->request->get('examen_solicitado_categoria') == 3){
                            $examenSolicitado->setCategoria("Macroscopico");
                        }
                        if($request->request->get('examen_solicitado_categoria') == 4){
                            $examenSolicitado->setCategoria("Cristaluria");
                        }
                        $entityManager->persist($examenSolicitado);
                        $entityManager->flush();
                        $this->addFlash('success', 'Examen añadido con exito');
                        return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);

                    }elseif(($request->request->get('examen_solicitado_tipoExamen') == 2 && $request->request->get('examen_solicitado_categoria') || ($request->request->get('examen_solicitado_tipoExamen') == 2 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 2 && $request->request->get('examen_solicitado_categoria') == 3) )){
                            $entityManager = $this->getDoctrine()->getManager();
                            $examenSolicitado->setTipoExamen("Heces");
                            if($request->request->get('examen_solicitado_categoria') == 1){
                            $examenSolicitado->setCategoria("Quimico");
                            }
                            if($request->request->get('examen_solicitado_categoria') == 2){
                                $examenSolicitado->setCategoria("Microscopico");   
                            }
                            if($request->request->get('examen_solicitado_categoria') == 3){
                                $examenSolicitado->setCategoria("Macroscopico");
                            }
                            $entityManager->persist($examenSolicitado);
                            $entityManager->flush();
                            $this->addFlash('success', 'Examen añadido con exito');
                            return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                    }elseif( ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == "" ) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == "" ) ){
                            $entityManager = $this->getDoctrine()->getManager();
                            $examenSolicitado->setCategoria("-");
                            if($request->request->get('examen_solicitado_tipoExamen') == 3){
                                $examenSolicitado->setTipoExamen("Quimica Sanguinea");
                            }else{
                                $examenSolicitado->setTipoExamen("Hematologico");
                            }
                            $entityManager->persist($examenSolicitado);
                            $entityManager->flush();
                            $this->addFlash('success', 'Examen añadido con exito');
                            return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);
                    }else{
                        if(($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 1) || ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 3) || ($request->request->get('examen_solicitado_tipoExamen') == 3 && $request->request->get('examen_solicitado_categoria') == 4) ){
                            $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                            return $this->render('examen_solicitado/edit.html.twig', [
                                'examen_solicitado' => $examenSolicitado,
                                'cita' => $cita,
                                'editar' => $editar,
                                'form' => $form->createView(),
                            ]);   
                        }elseif(($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 1) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 2) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 3) || ($request->request->get('examen_solicitado_tipoExamen') == 4 && $request->request->get('examen_solicitado_categoria') == 4)  ){
                            $this->addFlash('fail', 'Error este tipo de examen no posee ninguna categoria pruebe quitando la categoria o elija otro tipo de examen');
                            return $this->render('examen_solicitado/edit.html.twig', [
                                'examen_solicitado' => $examenSolicitado,
                                'cita' => $cita,
                                'editar' => $editar,
                                'form' => $form->createView(),
                            ]);
                        }elseif( ($request->request->get('examen_solicitado_tipoExamen') == 1 && $form["categoria"]->getData() == "" ) || ($request->request->get('examen_solicitado_tipoExamen') == 2 && $form["categoria"]->getData() == "" ) ){
                            $this->addFlash('fail', 'Error este tipo de examen posee categoria no puede ir vacia por favor seleccione una categoria.');
                            return $this->render('examen_solicitado/edit.html.twig', [
                                'examen_solicitado' => $examenSolicitado,
                                'cita' => $cita,
                                'editar' => $editar,
                                'form' => $form->createView(),
                            ]);
                        }else{
                            $this->addFlash('fail', 'Error este tipo de examen no posee examen de cristaluria');
                            return $this->render('examen_solicitado/edit.html.twig', [
                                'examen_solicitado' => $examenSolicitado,
                                'cita' => $cita,
                                'editar' => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }
                    
                }else{
                    $this->addFlash('fail', 'Error, el tipo de examen no puede ir vacio');
                    return $this->render('examen_solicitado/edit.html.twig', [
                        'examen_solicitado' => $examenSolicitado,
                        'cita' => $cita,
                        'editar' => $editar,
                        'form' => $form->createView(),
                    ]);
                }
            }

            return $this->render('examen_solicitado/edit.html.twig', [
                'examen_solicitado' => $examenSolicitado,
                'cita' => $cita,
                'editar' => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
    */

    /**
     * @Route("/{id}/{cita}", name="examen_solicitado_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenSolicitado $examenSolicitado, Cita $cita, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $cita->getExpediente()->getUsuario()->getClinica()->getId()){
                if($cita->getExpediente()->getHabilitado()){
                    
                    if ($this->isCsrfTokenValid('delete'.$examenSolicitado->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenSolicitado);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con exito');
                    return $this->redirectToRoute('examen_solicitado_index',['cita' => $cita->getId()]);

                }else{
                    $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }
        
    }
}
