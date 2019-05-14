<?php

namespace App\Controller;

use App\Entity\Cita;
use App\Entity\User;
use App\Entity\Especialidad;
use App\Entity\Expediente;
use App\Form\CitaType;
use App\Repository\CitaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

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
        date_default_timezone_set("America/El_Salvador");
        $especialidades = $this->getDoctrine()->getRepository(Especialidad::class)->findAll();
        $form = $this->createForm(CitaType::class, $citum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $horaSeleccionada =$request->request->get('time2')[3].$request->request->get('time2')[4];
            $fecha=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
            $fechaFin=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
            $hoy = date_create();
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND usuario_id=".$request->request->get('user').";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if($fecha < $hoy){
                $this->addFlash('fail','Error, la fecha y hora debe ser mayor o igual que la fecha y hora actual');
                return $this->render('cita/new.html.twig', [
                    'citum' => $citum,
                    'editar' => $editar,
                    'expediente' => $expediente,
                    'especialidades' => $especialidades,
                    'form' => $form->createView(),
                ]);

            }elseif ($horaSeleccionada != '00' && $horaSeleccionada != '30') {
                $this->addFlash('fail','Error, la hora ingresada no es valida, ingrese una hora puntual u hora y media');
                return $this->render('cita/new.html.twig', [
                    'citum' => $citum,
                    'editar' => $editar,
                    'expediente' => $expediente,
                    'especialidades' => $especialidades,
                    'form' => $form->createView(),
                ]);

            }elseif ($result != null) {
                $this->addFlash('fail','Error, el doctor que ha seleccionado no tiene disponible ese horario');
                return $this->render('cita/new.html.twig', [
                    'citum' => $citum,
                    'editar' => $editar,
                    'expediente' => $expediente,
                    'especialidades' => $especialidades,
                    'form' => $form->createView(),
                ]);
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $citum->setExpediente($this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()));
                $citum->setFechaReservacion($fecha);
                $citum->setFechaFin($fechaFin->modify('+30 minutes'));
                $citum->setConsultaPor($form["consultaPor"]->getData());
                $citum->setUsuario($this->getDoctrine()->getRepository(User::class)->find($request->request->get('user')));
                $entityManager->persist($citum);
                $entityManager->flush();

                return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
            }
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
        date_default_timezone_set("America/El_Salvador");
        $especialidades = $this->getDoctrine()->getRepository(Especialidad::class)->findAll();
        $form = $this->createForm(CitaType::class, $citum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($request->request->get('user') != "" && $request->request->get('especialidad') != ""){
                $horaSeleccionada =$request->request->get('time2')[3].$request->request->get('time2')[4];
                $fecha=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                $fechaFin=date_create($request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00");
                $hoy = date_create();
                $em = $this->getDoctrine()->getManager();
                $RAW_QUERY="SELECT * FROM `cita` WHERE fecha_reservacion='".$request->request->get('cita')["fechaReservacion"]." ".$request->request->get('time2').":00"."' AND usuario_id=".$request->request->get('user').";";
                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();
                if($fecha < $hoy){
                    $this->addFlash('fail','Error, la fecha y hora debe ser mayor o igual que la fecha y hora actual');
                    return $this->render('cita/new.html.twig', [
                        'citum' => $citum,
                        'editar' => $editar,
                        'expediente' => $expediente,
                        'especialidades' => $especialidades,
                        'form' => $form->createView(),
                    ]);

                }elseif ($horaSeleccionada != '00' && $horaSeleccionada != '30') {
                    $this->addFlash('fail','Error, la hora ingresada no es valida, ingrese una hora puntual u hora y media');
                    return $this->render('cita/new.html.twig', [
                        'citum' => $citum,
                        'editar' => $editar,
                        'expediente' => $expediente,
                        'especialidades' => $especialidades,
                        'form' => $form->createView(),
                    ]);

                }elseif ($result != null) {
                    $this->addFlash('fail','Error, el doctor que ha seleccionado no tiene disponible ese horario');
                    return $this->render('cita/new.html.twig', [
                        'citum' => $citum,
                        'editar' => $editar,
                        'expediente' => $expediente,
                        'especialidades' => $especialidades,
                        'form' => $form->createView(),
                    ]);
                }else{
                    $entityManager = $this->getDoctrine()->getManager();
                    $citum->setExpediente($this->getDoctrine()->getRepository(Expediente::class)->find($expediente->getId()));
                    $citum->setFechaReservacion($fecha);
                    $citum->setFechaFin($fechaFin->modify('+30 minutes'));
                    $citum->setConsultaPor($form["consultaPor"]->getData());
                    $citum->setUsuario($this->getDoctrine()->getRepository(User::class)->find($request->request->get('user')));
                    $entityManager->persist($citum);
                    $entityManager->flush();

                    return $this->redirectToRoute('cita_index',['expediente' => $expediente->getId()]);
                }
            }else{
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('cita_index', [
                'id' => $citum->getId(),
                'expediente' => $expediente->getId(),
            ]);
        }

        return $this->render('cita/edit.html.twig', [
            'citum' => $citum,
            'editar' => $editar,
            'user'  => $citum->getUsuario(),
            'especialidades' => $especialidades,
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
