<?php

namespace App\Controller;

use App\Entity\ExamenOrinaQuimico;
use App\Form\ExamenOrinaQuimicoType;
use App\Repository\ExamenOrinaQuimicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/orina/quimico")
 */
class ExamenOrinaQuimicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_orina_quimico_index", methods={"GET"})
     */
    public function index(ExamenOrinaQuimicoRepository $examenOrinaQuimicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_orina_quimico/index.html.twig', [
            'examen_orina_quimicos' => $examenOrinaQuimicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_orina_quimico_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $examenOrinaQuimico = new ExamenOrinaQuimico();
        $form = $this->createForm(ExamenOrinaQuimicoType::class, $examenOrinaQuimico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenOrinaQuimico);
            $entityManager->flush();

            return $this->redirectToRoute('examen_orina_quimico_index');
        }

        return $this->render('examen_orina_quimico/new.html.twig', [
            'examen_orina_quimico' => $examenOrinaQuimico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_quimico_show", methods={"GET"})
     */
    public function show(ExamenOrinaQuimico $examenOrinaQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_orina_quimico/show.html.twig', [
            'examen_orina_quimico' => $examenOrinaQuimico,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_orina_quimico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenOrinaQuimico $examenOrinaQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $form = $this->createForm(ExamenOrinaQuimicoType::class, $examenOrinaQuimico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_orina_quimico_index', [
                'id' => $examenOrinaQuimico->getId(),
            ]);
        }

        return $this->render('examen_orina_quimico/edit.html.twig', [
            'examen_orina_quimico' => $examenOrinaQuimico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_orina_quimico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenOrinaQuimico $examenOrinaQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenOrinaQuimico->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenOrinaQuimico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_orina_quimico_index');
    }
}
