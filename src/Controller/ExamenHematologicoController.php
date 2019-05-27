<?php

namespace App\Controller;

use App\Entity\ExamenHematologico;
use App\Form\ExamenHematologicoType;
use App\Repository\ExamenHematologicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/hematologico")
 */
class ExamenHematologicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_hematologico_index", methods={"GET"})
     */
    public function index(ExamenHematologicoRepository $examenHematologicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_hematologico/index.html.twig', [
            'examen_hematologicos' => $examenHematologicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_hematologico_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $examenHematologico = new ExamenHematologico();
        $form = $this->createForm(ExamenHematologicoType::class, $examenHematologico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenHematologico);
            $entityManager->flush();

            return $this->redirectToRoute('examen_hematologico_index');
        }

        return $this->render('examen_hematologico/new.html.twig', [
            'examen_hematologico' => $examenHematologico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_hematologico_show", methods={"GET"})
     */
    public function show(ExamenHematologico $examenHematologico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_hematologico/show.html.twig', [
            'examen_hematologico' => $examenHematologico,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_hematologico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenHematologico $examenHematologico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $form = $this->createForm(ExamenHematologicoType::class, $examenHematologico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_hematologico_index', [
                'id' => $examenHematologico->getId(),
            ]);
        }

        return $this->render('examen_hematologico/edit.html.twig', [
            'examen_hematologico' => $examenHematologico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_hematologico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenHematologico $examenHematologico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenHematologico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenHematologico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_hematologico_index');
    }
}
