<?php

namespace App\Controller;

use App\Entity\Anexo;
use App\Form\AnexoType;
use App\Entity\ExamenSolicitado;
use App\Repository\AnexoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/anexo")
 */
class AnexoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="anexo_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_ANEXO')")
     */
    public function index(AnexoRepository $anexoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {

        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $em = $this->getDoctrine()->getManager();
                    $RAW_QUERY = "SELECT examen.* FROM `anexo` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    return $this->render('anexo/index.html.twig', [
                        'anexos' => $result,
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

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT examen.* FROM `anexo` as examen WHERE examen_solicitado_id =".$examen_solicitado->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('anexo/index.html.twig', [
            'anexos' => $result,
            'user'                          => $AuthUser,
            'examen_solicitado'             => $examen_solicitado,
        ]);
    }

    /**
     * @Route("/new/{examen_solicitado}", name="anexo_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_ANEXO')")
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $anexo = new Anexo();
                    $form = $this->createForm(AnexoType::class, $anexo);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        if($form["ruta"]->getData() != ""){
                            $entityManager = $this->getDoctrine()->getManager();
                            $anexo->setExamenSolicitado($examen_solicitado);
                            $anexo->setNombreArchivo($form['ruta']->getData()->getClientOriginalName());

                            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                            $file = $form['ruta']->getData();
                            $filename= md5(uniqid()).'.'.$file->guessExtension();
                            $file->move($this->getParameter('image_directory'),$filename);
                            $anexo->setRuta($filename);
                            $entityManager->persist($anexo);
                            $entityManager->flush();

                            return $this->redirectToRoute('anexo_index',['examen_solicitado' => $examen_solicitado->getId()]);
                        }else{
                            $this->addFlash('fail', 'Error, agregue un archivo.');
                            return $this->render('anexo/new.html.twig', [
                                'anexo'             => $anexo,
                                'examen_solicitado' => $examen_solicitado,
                                'form'              => $form->createView(),
                            ]);
                        }
                        
                    }
                    return $this->render('anexo/new.html.twig', [
                        'anexo'             => $anexo,
                        'examen_solicitado' => $examen_solicitado,
                        'form'              => $form->createView(),
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
            $anexo = new Anexo();
            $form = $this->createForm(AnexoType::class, $anexo);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $anexo->setExamenSolicitado($examen_solicitado);
                $anexo->setNombreArchivo($form['ruta']->getData()->getClientOriginalName());
               // dd($form['ruta']->getData()->getClientOriginalName());


                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $form['ruta']->getData();
                $filename= md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('image_directory'),$filename);
                $anexo->setRuta($filename);
                $entityManager->persist($anexo);
                $entityManager->flush();

                return $this->redirectToRoute('anexo_index',['examen_solicitado' => $examen_solicitado->getId()]);
            }

            return $this->render('anexo/new.html.twig', [
                'anexo'             => $anexo,
                'examen_solicitado' => $examen_solicitado,
                'form'              => $form->createView(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
        
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="anexo_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_ANEXO')")
     */
    public function show(Anexo $anexo,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {   
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $anexo->getExamenSolicitado()->getId() == $examen_solicitado->getId()){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    $displayName = $anexo->getNombreArchivo();
                    $fileName = $anexo->getRuta();
                    $file_with_path = $this->getParameter ( 'image_directory' ) . "/" . $fileName;
                    $response = new BinaryFileResponse ( $file_with_path );
                    $response->headers->set ( 'Content-Type', 'text/plain' );
                    $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $displayName );
                    return $response;


                    return $this->render('anexo/show.html.twig', [
                        'anexo'             => $anexo,
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


        if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
            $displayName = $anexo->getNombreArchivo();
            $fileName = $anexo->getRuta();
            $file_with_path = $this->getParameter ( 'image_directory' ) . "/" . $fileName;
            $response = new BinaryFileResponse ( $file_with_path );
            $response->headers->set ( 'Content-Type', 'text/plain' );
            $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $displayName );
            return $response;


            return $this->render('anexo/show.html.twig', [
                'anexo'             => $anexo,
                'examen_solicitado' => $examen_solicitado,
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }

        
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="anexo_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_ANEXO')")
     */
    public function edit(Request $request, Anexo $anexo): Response
    {
        $form = $this->createForm(AnexoType::class, $anexo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('anexo_index', [
                'examen_solicitado' => $examen_solicitado,
                'id'                => $anexo->getId(),
            ]);
        }

        return $this->render('anexo/edit.html.twig', [
            'anexo'             => $anexo,
            'examen_solicitado' => $examen_solicitado,
            'form'              => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="anexo_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_ANEXO')")
     */
    public function delete(Request $request, ExamenSolicitado $examen_solicitado, Anexo $anexo): Response
    {
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $examen_solicitado->getCita()->getExpediente()->getUsuario()->getClinica()->getId() && $anexo->getExamenSolicitado()->getId() == $examen_solicitado->getId() ){
                if($examen_solicitado->getCita()->getExpediente()->getHabilitado()){
                    if ($this->isCsrfTokenValid('delete'.$anexo->getId(), $request->request->get('_token'))) {
                        $filename = $this->getParameter('image_directory').'/'.$anexo->getRuta();

                        $filesystem = new Filesystem();
                        $filesystem->remove($filename);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($anexo);
                        $entityManager->flush();
                    }

                    return $this->redirectToRoute('anexo_index',[
                        'examen_solicitado' => $examen_solicitado->getId(),
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
            if ($this->isCsrfTokenValid('delete'.$anexo->getId(), $request->request->get('_token'))) {
                $filename = $this->getParameter('image_directory').'/'.$anexo->getRuta();

                $filesystem = new Filesystem();
                $filesystem->remove($filename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($anexo);
                $entityManager->flush();
            }

            return $this->redirectToRoute('anexo_index',[
                'examen_solicitado' => $examen_solicitado->getId(),
            ]);
        }else{
            $this->addFlash('fail','Este paciente no esta habilitado, para poder hacer uso de el consulte con su superior para habilitar el paciente');
            return $this->redirectToRoute('home');
        }
    }
}
