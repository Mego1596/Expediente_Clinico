<?php

namespace App\Controller;

use App\Entity\Cita;
use App\Entity\Especialidad;
use App\Entity\Expediente;
use App\Form\CitaType;
use App\Repository\CitaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cita")
 */
class CitaController extends AbstractController
{
    /**
     * @Route("/{expediente}", name="cita_index", methods={"GET"})
     */
    public function index(CitaRepository $citaRepository,Expediente $expediente,Security $AuthUser): Response
    {

        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT id,consulta_por as consultaPor,fecha_reservacion as fechaReservacion,fecha_fin as fechaFin FROM cita WHERE expediente_id = ".$expediente->getId().";";

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('cita/index.html.twig', [
            'citas' => $result,
            'expediente' => $expediente,
            'user'       => $AuthUser,
        ]);
    }


    /**
     * @Route("/new/{expediente}", name="cita_new", methods={"GET","POST"})
     */
    public function new(Request $request,Expediente $expediente): Response
    {   $editar = false;
        $citum = new Cita();
        $especialidades = $this->getDoctrine()->getRepository(Especialidad::class)->findAll();
        $form = $this->createForm(CitaType::class, $citum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $citum->setExpediente($this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()));
            
            $entityManager->persist($citum);
            $entityManager->flush();

            return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
        }

        return $this->render('cita/new.html.twig', [
            'citum' => $citum,
            'editar' => $editar,
            'expediente' => $expediente,
            'especialidades' => $especialidades,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/calendar/{expediente}", name="cita_calendar", methods={"GET"})
     */
    public function calendar(Expediente $expediente): Response
    {   
        return $this->render('cita/calendar.html.twig',[
            'expediente' =>$expediente,
            ]);
    }

    /**
     * @Route("/{expediente}/{id}/", name="cita_show", methods={"GET"})
     */
    public function show(Cita $citum,Expediente $expediente): Response
    {
        return $this->render('cita/show.html.twig', [
            'citum' => $citum,
            'expediente' => $expediente,
        ]);
    }

    /**
     * @Route("/{expediente}/{id}/edit", name="cita_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cita $citum,Expediente $expediente): Response
    {
        $editar = true;
        $form = $this->createForm(CitaType::class, $citum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cita_index', [
                'id' => $citum->getId(),
                'expediente' => $expediente->getId(),
            ]);
        }

        return $this->render('cita/edit.html.twig', [
            'citum' => $citum,
            'editar' => $editar,
            'expediente' => $expediente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{expediente}/{id}", name="cita_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cita $citum,Expediente $expediente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$citum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($citum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
    }
}
