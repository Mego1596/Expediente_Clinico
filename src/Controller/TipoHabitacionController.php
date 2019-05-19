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
/**
 * @Route("/tipo/habitacion")
 */
class TipoHabitacionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_habitacion_index", methods={"GET"})
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
     */
    public function new(Request $request): Response
    {
        $editar = false;
        $tipoHabitacion = new TipoHabitacion();
        $form = $this->createForm(TipoHabitacionType::class, $tipoHabitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipoHabitacion);
            $entityManager->flush();

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
     */
    public function show(TipoHabitacion $tipoHabitacion): Response
    {
        return $this->render('tipo_habitacion/show.html.twig', [
            'tipo_habitacion' => $tipoHabitacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_habitacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoHabitacion $tipoHabitacion): Response
    {
        $editar = true;
        $form = $this->createForm(TipoHabitacionType::class, $tipoHabitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
     */
    public function delete(Request $request, TipoHabitacion $tipoHabitacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoHabitacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipoHabitacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_habitacion_index');
    }
}
