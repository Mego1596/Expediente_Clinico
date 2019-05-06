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
     * @Security2("has_role('ROLE_PERMISSION_INDEX_CLINICA')")
     */
    public function index(ClinicaRepository $clinicaRepository): Response
    {
        $user = $this->getUser();

        return $this->render('clinica/index.html.twig', [
            'clinicas' => $clinicaRepository->findAll(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/new", name="clinica_new", methods={"GET","POST"})
     * @Security2("has_role('ROLE_PERMISSION_NEW_CLINICA')")
     */
    public function new(Request $request): Response
    {
        $clinica = new Clinica();
        $form = $this->createForm(ClinicaType::class, $clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clinica);
            $entityManager->flush();

            $this->addFlash('success', 'Clinica creada con exito');
            return $this->redirectToRoute('clinica_index');
        }

        return $this->render('clinica/new.html.twig', [
            'clinica' => $clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinica_show", methods={"GET"})
     * @Security2("has_role('ROLE_PERMISSION_SHOW_CLINICA')")
     */
    public function show(Clinica $clinica): Response
    {
        return $this->render('clinica/show.html.twig', [
            'clinica' => $clinica,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="clinica_edit", methods={"GET","POST"})
     * @Security2("has_role('ROLE_PERMISSION_EDIT_CLINICA')")
     */
    public function edit(Request $request, Clinica $clinica): Response
    {
        $form = $this->createForm(ClinicaType::class, $clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Clinica modificada con exito');
            return $this->redirectToRoute('clinica_index', [
                'id' => $clinica->getId(),
            ]);
        }

        return $this->render('clinica/edit.html.twig', [
            'clinica' => $clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clinica_delete", methods={"DELETE"})
     * @Security2("has_role('ROLE_PERMISSION_DELETE_CLINICA')")
     */
    public function delete(Request $request, Clinica $clinica): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clinica->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($clinica);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Clinica eliminada con exito');
        return $this->redirectToRoute('clinica_index');
    }
}
