<?php

namespace App\Controller;

use App\Entity\ExamenOrinaMicroscopico;
use App\Form\ExamenOrinaMicroscopicoType;
use App\Repository\ExamenOrinaMicroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/orina/microscopico")
 */
class ExamenOrinaMicroscopicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_orina_microscopico_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_EXAMENES')")
     */
    public function index(examenOrinaMicroscopicoRepository $examenOrinaMicroscopicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){

                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_orina_microscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();

                    return $this->render('examen_orina_microscopico/index.html.twig', [
                        'examen_orina_microscopicos'    => $result,
                        'cantidad'                      => count($result),
                        'user'                          => $AuthUser,
                        'examen_solicitado'             => $examen_solicitado,
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

        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_orina_microscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            return $this->render('examen_orina_microscopico/index.html.twig', [
                'examen_orina_microscopicos'    => $result,
                'cantidad'                      => count($result),
                'user'                          => $AuthUser,
                'examen_solicitado'             => $examen_solicitado,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

    }


    /**
     * @Route("/new/{examen_solicitado}", name="examen_orina_microscopico_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_EXAMENES')")
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = false;
                    $examenOrinaMicroscopico = new examenOrinaMicroscopico();
                    $form = $this->createForm(examenOrinaMicroscopicoType::class, $examenOrinaMicroscopico);
                    $form->handleRequest($request);

                    //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `examen_orina_microscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    if(count($result) < 1){
                        if ($form->isSubmitted() && $form->isValid()) {
                            if($form["uretral"]->getData() != ""){
                                if($form["urotelio"]->getData() != ""){
                                    if($form["renal"]->getData() != ""){
                                        if($form["leucocitos"]->getData() != ""){
                                            if($form["piocitos"]->getData() != ""){
                                                if($form["eritrocitos"]->getData() != ""){
                                                    if($form["bacteria"]->getData() != ""){
                                                        if($form["parasitos"]->getData() != ""){
                                                            if($form["funguria"]->getData() != ""){
                                                                if($form["filamentoDeMucina"]->getData() != ""){
                                                                    if($form["proteinaUromocoide"]->getData() != ""){
                                                                        if($form["cilindros"]->getData() != ""){
                                                                            //PROCESAMIENTO DE DATOS
                                                                            $entityManager = $this->getDoctrine()->getManager();
                                                                            $examenOrinaMicroscopico->setExamenSolicitado($examen_solicitado);
                                                                            $entityManager->persist($examenOrinaMicroscopico);
                                                                            $entityManager->flush();
                                                                            //FIN DE PROCESAMIENTO DE DATOS
                                                                        }else{
                                                                            $this->addFlash('fail', 'Error, Cilindros no puede ir vacío');
                                                                            return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                                'examen_solicitado' => $examen_solicitado,
                                                                                'editar'            => $editar,
                                                                                'form' => $form->createView(),
                                                                            ]); 
                                                                        }
                                                                    }else{
                                                                        $this->addFlash('fail', 'Error, Proteína Uromucoide no puede ir vacío');
                                                                        return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                            'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                            'examen_solicitado' => $examen_solicitado,
                                                                            'editar'            => $editar,
                                                                            'form' => $form->createView(),
                                                                        ]); 
                                                                    }
                                                                }else{
                                                                    $this->addFlash('fail', 'Error, Filamento de Mucina no puede ir vacío');
                                                                    return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                        'examen_solicitado' => $examen_solicitado,
                                                                        'editar'            => $editar,
                                                                        'form' => $form->createView(),
                                                                    ]); 
                                                                }
                                                            }else{
                                                                $this->addFlash('fail', 'Error, Funguria no puede ir vacío');
                                                                return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                    'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                    'examen_solicitado' => $examen_solicitado,
                                                                    'editar'            => $editar,
                                                                    'form' => $form->createView(),
                                                                ]); 
                                                            }
                                                        }else{
                                                           $this->addFlash('fail', 'Error, Parásitos no puede ir vacío');
                                                            return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                'examen_solicitado' => $examen_solicitado,
                                                                'editar'            => $editar,
                                                                'form' => $form->createView(),
                                                            ]); 
                                                        }
                                                    }else{
                                                        $this->addFlash('fail', 'Error, Bacterias no puede ir vacío');
                                                        return $this->render('examen_orina_microscopico/new.html.twig', [
                                                            'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                            'examen_solicitado' => $examen_solicitado,
                                                            'editar'            => $editar,
                                                            'form' => $form->createView(),
                                                        ]);
                                                    }
                                                }else{
                                                    $this->addFlash('fail', 'Error, eritrocitos no puede ir vacío');
                                                    return $this->render('examen_orina_microscopico/new.html.twig', [
                                                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                        'examen_solicitado' => $examen_solicitado,
                                                        'editar'            => $editar,
                                                        'form' => $form->createView(),
                                                    ]);
                                                }
                                            }else{
                                                $this->addFlash('fail', 'Error, Piocitos no puede ir vacío');
                                                return $this->render('examen_orina_microscopico/new.html.twig', [
                                                    'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                    'examen_solicitado' => $examen_solicitado,
                                                    'editar'            => $editar,
                                                    'form' => $form->createView(),
                                                ]);
                                            }
                                        }else{
                                            $this->addFlash('fail', 'Error, Leucocitos no puede ir vacío');
                                            return $this->render('examen_orina_microscopico/new.html.twig', [
                                                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, Renal no puede ir vacío');
                                        return $this->render('examen_orina_microscopico/new.html.twig', [
                                            'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, Urotelio no puede ir vacío');
                                    return $this->render('examen_orina_microscopico/new.html.twig', [
                                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, Uretral no puede ir vacío');
                                return $this->render('examen_orina_microscopico/new.html.twig', [
                                    'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                            $this->addFlash('success', 'Examen añadido con éxito');
                            return $this->redirectToRoute('examen_orina_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o elimínelo si desea crear uno nuevo.');
                        return $this->redirectToRoute('examen_orina_microscopico_index', ['examen_solicitado' => $examen_solicitado->getId()]);
                    }

                    return $this->render('examen_orina_microscopico/new.html.twig', [
                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                        'examen_solicitado' => $examen_solicitado,
                        'editar'            => $editar,
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



        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $editar = false;
            $examenOrinaMicroscopico = new examenOrinaMicroscopico();
            $form = $this->createForm(examenOrinaMicroscopicoType::class, $examenOrinaMicroscopico);
            $form->handleRequest($request);

            //VALIDACION DE RUTA PARA NO INGRESAR SI YA EXISTE 1 REGISTRO 
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT examen.* FROM `examen_orina_microscopico` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if(count($result) < 1){
                if ($form->isSubmitted() && $form->isValid()) {
                    if($form["uretral"]->getData() != ""){
                        if($form["urotelio"]->getData() != ""){
                            if($form["renal"]->getData() != ""){
                                if($form["leucocitos"]->getData() != ""){
                                    if($form["piocitos"]->getData() != ""){
                                        if($form["eritrocitos"]->getData() != ""){
                                            if($form["bacteria"]->getData() != ""){
                                                if($form["parasitos"]->getData() != ""){
                                                    if($form["funguria"]->getData() != ""){
                                                        if($form["filamentoDeMucina"]->getData() != ""){
                                                            if($form["proteinaUromocoide"]->getData() != ""){
                                                                if($form["cilindros"]->getData() != ""){
                                                                    //PROCESAMIENTO DE DATOS
                                                                    $entityManager = $this->getDoctrine()->getManager();
                                                                    $examenOrinaMicroscopico->setExamenSolicitado($examen_solicitado);
                                                                    $entityManager->persist($examenOrinaMicroscopico);
                                                                    $entityManager->flush();
                                                                    //FIN DE PROCESAMIENTO DE DATOS
                                                                }else{
                                                                    $this->addFlash('fail', 'Error, Cilindros no puede ir vacío');
                                                                    return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                        'examen_solicitado' => $examen_solicitado,
                                                                        'editar'            => $editar,
                                                                        'form' => $form->createView(),
                                                                    ]); 
                                                                }
                                                            }else{
                                                                $this->addFlash('fail', 'Error, Proteína Uromucoide no puede ir vacío');
                                                                return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                    'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                    'examen_solicitado' => $examen_solicitado,
                                                                    'editar'            => $editar,
                                                                    'form' => $form->createView(),
                                                                ]); 
                                                            }
                                                        }else{
                                                            $this->addFlash('fail', 'Error, Filamento de Mucina no puede ir vacío');
                                                            return $this->render('examen_orina_microscopico/new.html.twig', [
                                                                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                                'examen_solicitado' => $examen_solicitado,
                                                                'editar'            => $editar,
                                                                'form' => $form->createView(),
                                                            ]); 
                                                        }
                                                    }else{
                                                        $this->addFlash('fail', 'Error, Funguria no puede ir vacío');
                                                        return $this->render('examen_orina_microscopico/new.html.twig', [
                                                            'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                            'examen_solicitado' => $examen_solicitado,
                                                            'editar'            => $editar,
                                                            'form' => $form->createView(),
                                                        ]); 
                                                    }
                                                }else{
                                                   $this->addFlash('fail', 'Error, Parásitos no puede ir vacío');
                                                    return $this->render('examen_orina_microscopico/new.html.twig', [
                                                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                        'examen_solicitado' => $examen_solicitado,
                                                        'editar'            => $editar,
                                                        'form' => $form->createView(),
                                                    ]); 
                                                }
                                            }else{
                                                $this->addFlash('fail', 'Error, Bacterias no puede ir vacío');
                                                return $this->render('examen_orina_microscopico/new.html.twig', [
                                                    'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                    'examen_solicitado' => $examen_solicitado,
                                                    'editar'            => $editar,
                                                    'form' => $form->createView(),
                                                ]);
                                            }
                                        }else{
                                            $this->addFlash('fail', 'Error, eritrocitos no puede ir vacío');
                                            return $this->render('examen_orina_microscopico/new.html.twig', [
                                                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                                'examen_solicitado' => $examen_solicitado,
                                                'editar'            => $editar,
                                                'form' => $form->createView(),
                                            ]);
                                        }
                                    }else{
                                        $this->addFlash('fail', 'Error, Piocitos no puede ir vacío');
                                        return $this->render('examen_orina_microscopico/new.html.twig', [
                                            'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                            'examen_solicitado' => $examen_solicitado,
                                            'editar'            => $editar,
                                            'form' => $form->createView(),
                                        ]);
                                    }
                                }else{
                                    $this->addFlash('fail', 'Error, Leucocitos no puede ir vacío');
                                    return $this->render('examen_orina_microscopico/new.html.twig', [
                                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                        'examen_solicitado' => $examen_solicitado,
                                        'editar'            => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                                $this->addFlash('fail', 'Error, Renal no puede ir vacío');
                                return $this->render('examen_orina_microscopico/new.html.twig', [
                                    'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                    'examen_solicitado' => $examen_solicitado,
                                    'editar'            => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, Urotelio no puede ir vacío');
                            return $this->render('examen_orina_microscopico/new.html.twig', [
                                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                                'examen_solicitado' => $examen_solicitado,
                                'editar'            => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, Uretral no puede ir vacío');
                        return $this->render('examen_orina_microscopico/new.html.twig', [
                            'examen_orina_microscopico' => $examenOrinaMicroscopico,
                            'examen_solicitado' => $examen_solicitado,
                            'editar'            => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                    $this->addFlash('success', 'Examen añadido con éxito');
                    return $this->redirectToRoute('examen_orina_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                }
            }else{
                $this->addFlash('fail', 'Error, ya se ha registrado un examen de este tipo por favor modifique el examen existente o elimínelo si desea crear uno nuevo.');
                return $this->redirectToRoute('examen_orina_microscopico_index', ['examen_solicitado' => $examen_solicitado->getId()]);
            }

            return $this->render('examen_orina_microscopico/new.html.twig', [
                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                'examen_solicitado' => $examen_solicitado,
                'editar'            => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_microscopico_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_EXAMENES')")
     */
    public function show(examenOrinaMicroscopico $examenOrinaMicroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaMicroscopico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    return $this->render('examen_orina_microscopico/show.html.twig', [
                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                        'examen_solicitado' => $examen_solicitado,
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
        return $this->render('examen_orina_microscopico/show.html.twig', [
            'examen_orina_microscopico' => $examenOrinaMicroscopico,
            'examen_solicitado' => $examen_solicitado,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_orina_microscopico_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_EXAMENES')")
     */
    public function edit(Request $request, examenOrinaMicroscopico $examenOrinaMicroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {   

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaMicroscopico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $editar = true;
                    $form = $this->createForm(examenOrinaMicroscopicoType::class, $examenOrinaMicroscopico);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Examen modificado con éxito');
                        return $this->redirectToRoute('examen_orina_microscopico_index', [
                            'id' => $examenOrinaMicroscopico->getId(),
                            'examen_solicitado' => $examen_solicitado->getId(),
                        ]);
                    }
                    return $this->render('examen_orina_microscopico/edit.html.twig', [
                        'examen_orina_microscopico' => $examenOrinaMicroscopico,
                        'examen_solicitado' => $examen_solicitado,
                        'editar'            => $editar,
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
        
        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $editar = true;
            $form = $this->createForm(examenOrinaMicroscopicoType::class, $examenOrinaMicroscopico);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Examen modificado con éxito');
                return $this->redirectToRoute('examen_orina_microscopico_index', [
                    'id' => $examenOrinaMicroscopico->getId(),
                    'examen_solicitado' => $examen_solicitado->getId(),
                ]);
            }
            return $this->render('examen_orina_microscopico/edit.html.twig', [
                'examen_orina_microscopico' => $examenOrinaMicroscopico,
                'examen_solicitado' => $examen_solicitado,
                'editar'            => $editar,
                'form' => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }



    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_microscopico_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_EXAMENES')")
     */
    public function delete(Request $request, examenOrinaMicroscopico $examenOrinaMicroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $examenOrinaMicroscopico->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$examenOrinaMicroscopico->getId(), $request->request->get('_token'))) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($examenOrinaMicroscopico);
                        $entityManager->flush();
                    }
                    $this->addFlash('success', 'Examen eliminado con éxito');
                    return $this->redirectToRoute('examen_orina_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
                }else{
                    $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
                    return $this->redirectToRoute('home');
                }
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('home');
            }  
        }

       if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            if ($this->isCsrfTokenValid('delete'.$examenOrinaMicroscopico->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($examenOrinaMicroscopico);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Examen eliminado con éxito');
            return $this->redirectToRoute('examen_orina_microscopico_index',['examen_solicitado' => $examen_solicitado->getId()]);
        }else{
            $this->addFlash('fail','Este paciente no está habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
