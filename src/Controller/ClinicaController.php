<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use App\Entity\Clinica;
use App\Form\ClinicaType;
use App\Repository\ClinicaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/clinica")
 */
class ClinicaController extends AbstractController
{
    /**
     * @Route("/", name="clinica_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_CLINICA')")
     */
    public function index(ClinicaRepository $clinicaRepository, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            $clinicas = $clinicaRepository->findBy(['id' => $AuthUser->getUser()->getClinica()->getId()]);
        }else{
            $clinicas = $clinicaRepository->findAll();
        }   
        return $this->render('clinica/index.html.twig', [
            'clinicas' => $clinicas,
            'user' => $AuthUser,
        ]);
    }

    /**
     * @Route("/new", name="clinica_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_CLINICA')")
     */
    public function new(Request $request): Response
    {
        $editar = false;
        $clinica = new Clinica();
        $form = $this->createForm(ClinicaType::class, $clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form["nombreClinica"]->getData() != ""){
                if($form["direccion"]->getData() != ""){
                    if($form["telefono"]->getData() != ""){
                        if($form["email"]->getData() != ""){
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($clinica);
                            $entityManager->flush();
                        }else{
                            $this->addFlash('fail', 'Error, el email no puede ir vacío');
                            return $this->render('clinica/new.html.twig', [
                                'clinica' => $clinica,
                                'editar' => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, el teléfono de contacto no puede ir vacío');
                        return $this->render('clinica/new.html.twig', [
                            'clinica' => $clinica,
                            'editar' => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error, la dirección no puede ir vacía');
                    return $this->render('clinica/new.html.twig', [
                        'clinica' => $clinica,
                        'editar' => $editar,
                        'form' => $form->createView(),
                    ]);
                }
            }else{
                $this->addFlash('fail', 'Error, el nombre de la clínica no puede ir vacío');
                return $this->render('clinica/new.html.twig', [
                    'clinica' => $clinica,
                    'editar' => $editar,
                    'form' => $form->createView(),
                ]);
            }
            $this->addFlash('success', 'Clínica creada con éxito');
            return $this->redirectToRoute('clinica_index');
        }

        return $this->render('clinica/new.html.twig', [
            'clinica' => $clinica,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinica_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_CLINICA')")
     */
    public function show(Clinica $clinica, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId()){
                return $this->render('clinica/show.html.twig', [
                    'clinica' => $clinica,
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('clinica_index', [
                    'id' => $clinica->getId(),
                ]);
            }
        }
        return $this->render('clinica/show.html.twig', [
            'clinica' => $clinica,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="clinica_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_CLINICA')")
     */
    public function edit(Request $request, Clinica $clinica, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId()){
                $editar = true;
                $form = $this->createForm(ClinicaType::class, $clinica);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();

                    $this->addFlash('success', 'Clínica modificada con éxito');
                    return $this->redirectToRoute('clinica_index', [
                        'id' => $clinica->getId(),
                    ]);
                }
                return $this->render('clinica/edit.html.twig', [
                    'clinica' => $clinica,
                    'editar' => $editar,
                    'form' => $form->createView(),
                ]);
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('clinica_index', [
                    'id' => $clinica->getId(),
                ]);
            }
        }
        //FIN VALIDACION
        $editar = true;
        $form = $this->createForm(ClinicaType::class, $clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Clínica modificada con éxito');
            return $this->redirectToRoute('clinica_index', [
                'id' => $clinica->getId(),
            ]);
        }
        return $this->render('clinica/edit.html.twig', [
            'clinica' => $clinica,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinica_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_CLINICA')")
     */
    public function delete(Request $request, Clinica $clinica, Security $AuthUser): Response
    {
        //VALIDACION DE REGISTROS UNICAMENTE DE MI CLINICA SI NO SOY ROLE_SA
        if($AuthUser->getUser()->getRol()->getNombreRol() != 'ROLE_SA'){
            if($AuthUser->getUser()->getClinica()->getId() == $clinica->getId()){
                if ($this->isCsrfTokenValid('delete'.$clinica->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($clinica);
                    $entityManager->flush();
                }
                $this->addFlash('success', 'Clínica eliminada con éxito');
                return $this->redirectToRoute('clinica_index');
            }else{
                $this->addFlash('fail','Error, este registro puede que no exista o no le pertenece');
                return $this->redirectToRoute('clinica_index', [
                    'id' => $clinica->getId(),
                ]);
            }
        }
        //FIN VALIDACION
        if ($this->isCsrfTokenValid('delete'.$clinica->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($clinica);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Clínica eliminada con éxito');
        return $this->redirectToRoute('clinica_index');
    }
}
