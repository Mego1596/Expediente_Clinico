<?php

namespace App\Controller;

use App\Entity\ExamenOrinaMacroscopico;
use App\Form\ExamenOrinaMacroscopicoType;
use App\Repository\ExamenOrinaMacroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/examen/orina/macroscopico")
 */
class ExamenOrinaMacroscopicoController extends AbstractController
{
    /**
     * @Route("/", name="examen_orina_macroscopico_index", methods={"GET"})
     */
    public function index(ExamenOrinaMacroscopicoRepository $examenOrinaMacroscopicoRepository): Response
    {
        return $this->render('examen_orina_macroscopico/index.html.twig', [
            'examen_orina_macroscopicos' => $examenOrinaMacroscopicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="examen_orina_macroscopico_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $examenOrinaMacroscopico = new ExamenOrinaMacroscopico();
        $form = $this->createForm(ExamenOrinaMacroscopicoType::class, $examenOrinaMacroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenOrinaMacroscopico);
            $entityManager->flush();

            return $this->redirectToRoute('examen_orina_macroscopico_index');
        }

        return $this->render('examen_orina_macroscopico/new.html.twig', [
            'examen_orina_macroscopico' => $examenOrinaMacroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_orina_macroscopico_show", methods={"GET"})
     */
    public function show(ExamenOrinaMacroscopico $examenOrinaMacroscopico): Response
    {
        return $this->render('examen_orina_macroscopico/show.html.twig', [
            'examen_orina_macroscopico' => $examenOrinaMacroscopico,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="examen_orina_macroscopico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenOrinaMacroscopico $examenOrinaMacroscopico): Response
    {
        $form = $this->createForm(ExamenOrinaMacroscopicoType::class, $examenOrinaMacroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_orina_macroscopico_index', [
                'id' => $examenOrinaMacroscopico->getId(),
            ]);
        }

        return $this->render('examen_orina_macroscopico/edit.html.twig', [
            'examen_orina_macroscopico' => $examenOrinaMacroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_orina_macroscopico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenOrinaMacroscopico $examenOrinaMacroscopico): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenOrinaMacroscopico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenOrinaMacroscopico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_orina_macroscopico_index');
    }
}
