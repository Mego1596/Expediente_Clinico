<?php

namespace App\Controller;

use App\Entity\ExamenQuimicaSanguinea;
use App\Form\ExamenQuimicaSanguineaType;
use App\Repository\ExamenQuimicaSanguineaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ExamenSolicitado;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/examen/quimica/sanguinea")
 */
class ExamenQuimicaSanguineaController extends AbstractController
{
    /**
     * @Route("/{examen_solicitado}", name="examen_quimica_sanguinea_index", methods={"GET"})
     */
    public function index(ExamenQuimicaSanguineaRepository $examenQuimicaSanguineaRepository,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_quimica_sanguinea/index.html.twig', [
            'examen_quimica_sanguineas' => $examenQuimicaSanguineaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{examen_solicitado}", name="examen_quimica_sanguinea_new", methods={"GET","POST"})
     */
    public function new(Request $request,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $examenQuimicaSanguinea = new ExamenQuimicaSanguinea();
        $form = $this->createForm(ExamenQuimicaSanguineaType::class, $examenQuimicaSanguinea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examenQuimicaSanguinea);
            $entityManager->flush();

            return $this->redirectToRoute('examen_quimica_sanguinea_index');
        }

        return $this->render('examen_quimica_sanguinea/new.html.twig', [
            'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_quimica_sanguinea_show", methods={"GET"})
     */
    public function show(ExamenQuimicaSanguinea $examenQuimicaSanguinea,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        return $this->render('examen_quimica_sanguinea/show.html.twig', [
            'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}/edit", name="examen_quimica_sanguinea_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExamenQuimicaSanguinea $examenQuimicaSanguinea,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        $form = $this->createForm(ExamenQuimicaSanguineaType::class, $examenQuimicaSanguinea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('examen_quimica_sanguinea_index', [
                'id' => $examenQuimicaSanguinea->getId(),
            ]);
        }

        return $this->render('examen_quimica_sanguinea/edit.html.twig', [
            'examen_quimica_sanguinea' => $examenQuimicaSanguinea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{examen_solicitado}", name="examen_quimica_sanguinea_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExamenQuimicaSanguinea $examenQuimicaSanguinea,ExamenSolicitado $examen_solicitado, Security $AuthUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenQuimicaSanguinea->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($examenQuimicaSanguinea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_quimica_sanguinea_index');
    }
}
