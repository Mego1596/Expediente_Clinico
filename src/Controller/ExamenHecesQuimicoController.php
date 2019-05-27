<?php

namespace App\Controller;

use App\Entity\ExamenHecesQuimico;
use App\Form\ExamenHecesQuimicoType;
use App\Repository\ExamenHecesQuimicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
/**
 * @Route("/examen/heces/quimico")
 */
class ExamenHecesQuimicoController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_heces_quimico_index", methods={"GET"})
     */
    public function index(ExamenHecesQuimicoRepository $examenHecesQuimicoRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_heces_quimico/index.html.twig', [
            'examen_heces_quimicos' => $examenHecesQuimicoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_heces_quimico_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $examenHecesQuimico = new ExamenHecesQuimico();
        $form = $this->createForm(ExamenHecesQuimicoType::class, $examenHecesQuimico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenHecesQuimico);
            $entityManager->flush();

            return $this->redirectToRoute('examen_heces_quimico_index');
        }

        return $this->render('examen_heces_quimico/new.html.twig', [
            'examen_heces_quimico' => $examenHecesQuimico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_quimico_show", methods={"GET"})
     */
    public function show(ExamenHecesQuimico $examenHecesQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_heces_quimico/show.html.twig', [
            'examen_heces_quimico' => $examenHecesQuimico,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_heces_quimico_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenHecesQuimico $examenHecesQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $form = $this->createForm(ExamenHecesQuimicoType::class, $examenHecesQuimico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_heces_quimico_index', [
                'idExamen_Heces_Quimico' => $examenHecesQuimico->getIdExamen_Heces_Quimico(),
            ]);
        }

        return $this->render('examen_heces_quimico/edit.html.twig', [
            'examen_heces_quimico' => $examenHecesQuimico,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_heces_quimico_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenHecesQuimico $examenHecesQuimico,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenHecesQuimico->getIdExamen_Heces_Quimico(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenHecesQuimico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_heces_quimico_index');
    }
}
