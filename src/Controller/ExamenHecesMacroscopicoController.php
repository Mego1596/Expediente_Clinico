<?php

namespace App\Controller;

use App\Entity\ExamenHecesMacroscopico;
use App\Entity\ExamenSolicitado;
use App\Form\ExamenHecesMacroscopicoType;
use App\Repository\ExamenHecesMacroscopicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/heces/macroscopico")
 */
class ExamenHecesMacroscopicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_heces_macroscopico_index", methods={"GET"})
     */
    public function index(ExamenHecesMacroscopicoRepository $examenHecesMacroscopicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_heces_macroscopico/index.html.twig', [
            'examen_heces_macroscopicos' => $examenHecesMacroscopicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_heces_macroscopico_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
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
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_macroscopico_show", methods={"GET"})
     */
    public function show(ExamenHecesMacroscopico $examenHecesMacroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_heces_macroscopico/show.html.twig', [
            'examen_heces_macroscopico' => $examenHecesMacroscopico,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_heces_macroscopico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenHecesMacroscopico $examenHecesMacroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
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
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_macroscopico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenHecesMacroscopico $examenHecesMacroscopico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenHecesMacroscopico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenHecesMacroscopico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_heces_macroscopico_index');
    }
}
