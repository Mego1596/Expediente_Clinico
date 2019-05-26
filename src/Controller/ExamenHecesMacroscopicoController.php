<?php

namespace App\Controller;

use App\Entity\ExamenHecesMacroscopico;
use App\Form\ExamenHecesMacroscopicoType;
use App\Repository\ExamenHecesMacroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/examen/heces/macroscopico")
 */
class ExamenHecesMacroscopicoController extends AbstractController
{
    /**
     * @Route("/", name="examen_heces_macroscopico_index", methods={"GET"})
     */
    public function index(ExamenHecesMacroscopicoRepository $examenHecesMacroscopicoRepository): Response
    {
        return $this->render('examen_heces_macroscopico/index.html.twig', [
            'examen_heces_macroscopicos' => $examenHecesMacroscopicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="examen_heces_macroscopico_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $examenHecesMacroscopico = new ExamenHecesMacroscopico();
        $form = $this->createForm(ExamenHecesMacroscopicoType::class, $examenHecesMacroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenHecesMacroscopico);
            $entityManager->flush();

            return $this->redirectToRoute('examen_heces_macroscopico_index');
        }

        return $this->render('examen_heces_macroscopico/new.html.twig', [
            'examen_heces_macroscopico' => $examenHecesMacroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_heces_macroscopico_show", methods={"GET"})
     */
    public function show(ExamenHecesMacroscopico $examenHecesMacroscopico): Response
    {
        return $this->render('examen_heces_macroscopico/show.html.twig', [
            'examen_heces_macroscopico' => $examenHecesMacroscopico,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="examen_heces_macroscopico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenHecesMacroscopico $examenHecesMacroscopico): Response
    {
        $form = $this->createForm(ExamenHecesMacroscopicoType::class, $examenHecesMacroscopico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_heces_macroscopico_index', [
                'id' => $examenHecesMacroscopico->getId(),
            ]);
        }

        return $this->render('examen_heces_macroscopico/edit.html.twig', [
            'examen_heces_macroscopico' => $examenHecesMacroscopico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="examen_heces_macroscopico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenHecesMacroscopico $examenHecesMacroscopico): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenHecesMacroscopico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenHecesMacroscopico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_heces_macroscopico_index');
    }
}
