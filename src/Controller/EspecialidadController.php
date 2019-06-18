<?php

namespace App\Controller;

use App\Entity\Especialidad;
use App\Form\EspecialidadType;
use App\Repository\EspecialidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/especialidad")
 */
class EspecialidadController extends AbstractController
{
    /**
     * @Route("/", name="especialidad_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_ESPECIALIDAD')")
     */
    public function index(EspecialidadRepository $especialidadRepository, Security $AuthUser): Response
    {
        return $this->render('especialidad/index.html.twig', [
            'especialidads' => $especialidadRepository->findAll(),
            'user' => $AuthUser,
        ]);
    }

    /**
     * @Route("/new", name="especialidad_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_ESPECIALIDAD')")
     */
    public function new(Request $request): Response
    {   
        $editar = false;
        $especialidad = new Especialidad();
        $form = $this->createForm(EspecialidadType::class, $especialidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($form["nombreEspecialidad"]->getData() != ""){
                $entityManager->persist($especialidad);
                $entityManager->flush();
                $this->addFlash('success', 'Especialidad agregada con éxito');
                return $this->redirectToRoute('especialidad_index');
            }else{
                $this->addFlash('fail', 'El nombre de la especialidad no puede estar vacío');
            }
        }
        return $this->render('especialidad/new.html.twig', [
            'especialidad' => $especialidad,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="especialidad_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_ESPECIALIDAD')")
     */
    public function show(Especialidad $especialidad): Response
    {
        $form = $this->createFormBuilder($especialidad)
        ->add('nombreEspecialidad',TextType::class, array('attr'=> array('class'=> 'form-control','disabled' => true)))
        ->getForm();
        return $this->render('especialidad/show.html.twig', [
            'especialidad' => $especialidad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="especialidad_edit", methods={"GET","POST"})
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_ESPECIALIDAD')")
     */
    public function edit(Request $request, Especialidad $especialidad): Response
    {
        $editar=true;
        $form = $this->createForm(EspecialidadType::class, $especialidad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Especialidad modificada con éxito');
            return $this->redirectToRoute('especialidad_index', [
                'id' => $especialidad->getId(),
            ]);
        }

        return $this->render('especialidad/edit.html.twig', [
            'especialidad' => $especialidad,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="especialidad_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_ESPECIALIDAD')")
     */
    public function delete(Request $request, Especialidad $especialidad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$especialidad->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($especialidad);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Especialidad eliminada con éxito');
        return $this->redirectToRoute('especialidad_index');
    }
}
