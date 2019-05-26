<?php

namespace App\Controller;

use App\Entity\ExamenOrinaMicroscopico;
use App\Form\ExamenOrinaMicroscopicoType;
use App\Repository\ExamenOrinaMicroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/examen/orina/microscopico")
 */
class ExamenOrinaMicroscopicoController extends AbstractController
{
    /**
     * @Route("/", name="examen_orina_microscopico_index", methods={"GET"})
     */
    public function index(ExamenOrinaMicroscopicoRepository $examenOrinaMicroscopicoRepository): Response
    {
        return $this->render('examen_orina_microscopico/index.html.twig', [
            'examen_orina_microscopicos' => $examenOrinaMicroscopicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="examen_orina_microscopico_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $examenOrinaMicroscopico = new ExamenOrinaMicroscopico();
        $form = $this->createForm(ExamenOrinaMicroscopicoType::class, $examenOrinaMicroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenOrinaMicroscopico);
            $entityManager->flush();

            return $this->redirectToRoute('examen_orina_microscopico_index');
        }

        return $this->render('examen_orina_microscopico/new.html.twig', [
            'examen_orina_microscopico' => $examenOrinaMicroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_orina_microscopico_show", methods={"GET"})
     */
    public function show(ExamenOrinaMicroscopico $examenOrinaMicroscopico): Response
    {
        return $this->render('examen_orina_microscopico/show.html.twig', [
            'examen_orina_microscopico' => $examenOrinaMicroscopico,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="examen_orina_microscopico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenOrinaMicroscopico $examenOrinaMicroscopico): Response
    {
        $form = $this->createForm(ExamenOrinaMicroscopicoType::class, $examenOrinaMicroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_orina_microscopico_index', [
                'id' => $examenOrinaMicroscopico->getId(),
            ]);
        }

        return $this->render('examen_orina_microscopico/edit.html.twig', [
            'examen_orina_microscopico' => $examenOrinaMicroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_orina_microscopico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenOrinaMicroscopico $examenOrinaMicroscopico): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenOrinaMicroscopico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenOrinaMicroscopico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_orina_microscopico_index');
    }
}
