<?php

namespace App\Controller;

use App\Entity\ExamenHecesMicroscopico;
use App\Form\ExamenHecesMicroscopicoType;
use App\Repository\ExamenHecesMicroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/examen/heces/microscopico")
 */
class ExamenHecesMicroscopicoController extends AbstractController
{
    /**
     * @Route("/", name="examen_heces_microscopico_index", methods={"GET"})
     */
    public function index(ExamenHecesMicroscopicoRepository $examenHecesMicroscopicoRepository): Response
    {
        return $this->render('examen_heces_microscopico/index.html.twig', [
            'examen_heces_microscopicos' => $examenHecesMicroscopicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="examen_heces_microscopico_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $examenHecesMicroscopico = new ExamenHecesMicroscopico();
        $form = $this->createForm(ExamenHecesMicroscopicoType::class, $examenHecesMicroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenHecesMicroscopico);
            $entityManager->flush();

            return $this->redirectToRoute('examen_heces_microscopico_index');
        }

        return $this->render('examen_heces_microscopico/new.html.twig', [
            'examen_heces_microscopico' => $examenHecesMicroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_heces_microscopico_show", methods={"GET"})
     */
    public function show(ExamenHecesMicroscopico $examenHecesMicroscopico): Response
    {
        return $this->render('examen_heces_microscopico/show.html.twig', [
            'examen_heces_microscopico' => $examenHecesMicroscopico,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="examen_heces_microscopico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenHecesMicroscopico $examenHecesMicroscopico): Response
    {
        $form = $this->createForm(ExamenHecesMicroscopicoType::class, $examenHecesMicroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_heces_microscopico_index', [
                'id' => $examenHecesMicroscopico->getId(),
            ]);
        }

        return $this->render('examen_heces_microscopico/edit.html.twig', [
            'examen_heces_microscopico' => $examenHecesMicroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_heces_microscopico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenHecesMicroscopico $examenHecesMicroscopico): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenHecesMicroscopico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenHecesMicroscopico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_heces_microscopico_index');
    }
}
