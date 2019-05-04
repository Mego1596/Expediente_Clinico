<?php

namespace App\Controller;

use App\Entity\Rol;
use App\Form\RolType;
use App\Repository\RolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rol")
 */
class RolController extends AbstractController
{
    /**
     * @Route("/", name="rol_index", methods={"GET"})
     */
    public function index(RolRepository $rolRepository): Response
    {
        return $this->render('rol/index.html.twig', [
            'rols' => $rolRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="rol_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rol = new Rol();
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rol);
            $entityManager->flush();

            return $this->redirectToRoute('rol_index');
        }

        return $this->render('rol/new.html.twig', [
            'rol' => $rol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rol_show", methods={"GET"})
     */
    public function show(Rol $rol): Response
    {
        return $this->render('rol/show.html.twig', [
            'rol' => $rol,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rol_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rol $rol): Response
    {
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rol_index', [
                'id' => $rol->getId(),
            ]);
        }

        return $this->render('rol/edit.html.twig', [
            'rol' => $rol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rol_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rol $rol): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rol->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rol_index');
    }
}
