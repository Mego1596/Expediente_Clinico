<?php

namespace App\Controller;

use App\Entity\TipoHabitacion;
use App\Form\TipoHabitacionType;
use App\Repository\TipoHabitacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/tipo/habitacion")
 */
class TipoHabitacionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_habitacion_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_TIPO_HABITACION')")
     */
    public function index(TipoHabitacionRepository $tipoHabitacionRepository,Security $AuthUser): Response
    {
        return $this->render('tipo_habitacion/index.html.twig', [
            'tipo_habitaciones' => $tipoHabitacionRepository->findAll(),
            'user' => $AuthUser,
        ]);
    }

    /**
     * @Route("/new", name="tipo_habitacion_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_TIPO_HABITACION')")
     */
    public function new(Request $request): Response
    {
        $editar = false;
        $tipoHabitacion = new TipoHabitacion();
        $form = $this->createForm(TipoHabitacionType::class, $tipoHabitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($form["tipoHabitacion"]->getData() != ""){
                $entityManager->persist($tipoHabitacion);
                $entityManager->flush();
            }else{
                $this->addFlash('fail','Error, el tipo de habitación no puede estar vacio');
                return $this->render('tipo_habitacion/new.html.twig', [
                    'tipo_habitacion' => $tipoHabitacion,
                    'editar' => $editar,
                    'form' => $form->createView(),
                ]);
            }
            $this->addFlash('success','Tipo de Habitación añadido con éxito');
            return $this->redirectToRoute('tipo_habitacion_index');
        }

        return $this->render('tipo_habitacion/new.html.twig', [
            'tipo_habitacion' => $tipoHabitacion,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_habitacion_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_TIPO_HABITACION')")
     */
    public function show(TipoHabitacion $tipoHabitacion): Response
    {
        return $this->render('tipo_habitacion/show.html.twig', [
            'tipo_habitacion' => $tipoHabitacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_habitacion_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_TIPO_HABITACION')")
     */
    public function edit(Request $request, TipoHabitacion $tipoHabitacion): Response
    {
        $editar = true;
        $form = $this->createForm(TipoHabitacionType::class, $tipoHabitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Tipo de Habitación modificado con éxito');
            return $this->redirectToRoute('tipo_habitacion_index', [
                'id' => $tipoHabitacion->getId(),
            ]);
        }

        return $this->render('tipo_habitacion/edit.html.twig', [
            'tipo_habitacion' => $tipoHabitacion,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_habitacion_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta está inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_TIPO_HABITACION')")
     */
    public function delete(Request $request, TipoHabitacion $tipoHabitacion): Response
    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT COUNT(*) FROM habitacion as h, tipo_habitacion as th WHERE 
        h.tipo_habitacion_id = th.id AND
        th.id = ".$tipoHabitacion->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $validacionBloqueoEliminar = $statement->fetchAll();

        if($validacionBloqueoEliminar > 1){
            $this->addFlash('notice','Para borrar el tipo de habitación verifique que este no tenga habitaciones asociadas a el');
            return $this->redirectToRoute('tipo_habitacion_index');
        }else{
            if ($this->isCsrfTokenValid('delete'.$tipoHabitacion->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($tipoHabitacion);
                $entityManager->flush();
            }
            $this->addFlash('success','Tipo de Habitación eliminado con éxito');
            return $this->redirectToRoute('tipo_habitacion_index');
        }
    }
}
