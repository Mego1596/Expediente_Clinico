<?php

namespace App\Controller;

use App\Entity\Camilla;
use App\Entity\Habitacion;
use App\Repository\CamillaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/camilla")
 */
class CamillaController extends AbstractController
{
    /**
     * @Route("/{habitacion}", name="camilla_index", methods={"GET"})
     */
    public function index(CamillaRepository $camillaRepository,Security $AuthUser, Habitacion $habitacion): Response
    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT * FROM camilla WHERE habitacion_id = ".$habitacion->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();

        return $this->render('camilla/index.html.twig', [
            'camillas' => $result,
            'user'      => $AuthUser,
            'habitacion' => $habitacion,
        ]);
    }

    /**
     * @Route("/new/{habitacion}", name="camilla_new", methods={"GET","POST"})
     */
    public function new(Request $request,Habitacion $habitacion): Response
    {
        $editar=false;
        $camilla = new Camilla();
        $form = $this->createFormBuilder($camilla)
        ->add('numeroCamilla', IntegerType::class, array('attr' => array('class' => 'form-control','min'=>1)))
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //VALIDACION PARA COMPROBAR SI ESE NUMERO DE CAMILLA YA EXISTE
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT c.* FROM camilla as c, habitacion as h, sala as s, clinica as cli 
            WHERE c.habitacion_id=h.id AND h.sala_id=s.id AND s.clinica_id = cli.id 
            AND cli.id =".$habitacion->getSala()->getClinica()->getId().
            " AND numero_camilla = ".$form["numeroCamilla"]->getData().";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            if($result == null){
                $camilla->setNumeroCamilla($form["numeroCamilla"]->getData());
            }else{
                $this->addFlash('fail','Error, esta camilla ya esta asignada por favor ingrese una nueva camilla');
                return $this->render('camilla/new.html.twig', [
                    'camilla' => $camilla,
                    'habitacion'    => $habitacion,
                    'editar'        => $editar,
                    'form' => $form->createView(),
                ]);
            }
            //FIN VALIDACION PARA COMPROBAR SI ESE NUMERO DE CAMILLA YA EXISTE
            $this->addFlash('success','Camilla añadida con exito');
            $camilla->setHabitacion($habitacion);
            $entityManager->persist($camilla);
            $entityManager->flush();

            return $this->redirectToRoute('camilla_index',['habitacion' => $habitacion->getId()]);
        }

        return $this->render('camilla/new.html.twig', [
            'camilla' => $camilla,
            'habitacion'    => $habitacion,
            'editar'        => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{habitacion}", name="camilla_show", methods={"GET"})
     */
    public function show(Camilla $camilla, Habitacion $habitacion): Response
    {
        return $this->render('camilla/show.html.twig', [
            'camilla' => $camilla,
            'habitacion'    => $habitacion,
        ]);
    }

    /**
     * @Route("/{id}/{habitacion}/edit", name="camilla_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Camilla $camilla, Habitacion $habitacion): Response
    {   
        $editar=true;
        $form = $this->createFormBuilder($camilla)
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //VALIDACION PARA COMPROBAR SI ESE NUMERO DE CAMILLA YA EXISTE
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT c.* FROM camilla as c, habitacion as h, sala as s, clinica as cli 
            WHERE c.habitacion_id=h.id AND h.sala_id=s.id AND s.clinica_id = cli.id 
            AND cli.id =".$habitacion->getSala()->getClinica()->getId().
            " AND numero_camilla = ".$request->request->get('numeroCambio').";";
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if($result != null){
                //VERIFICACION PARA OBTENER LA CAMILLA QUE SE VA A TRANSFERIR A LA HABITACION ACTUAL
                    $RAW_QUERY = "SELECT c.* FROM camilla as c, habitacion as h, sala as s, clinica as cli 
                    WHERE c.habitacion_id=h.id AND h.sala_id=s.id AND s.clinica_id = cli.id 
                    AND cli.id =".$habitacion->getSala()->getClinica()->getId().
                    " AND numero_camilla = ".$request->request->get('numeroCambio').";";
                    $statement = $em->getConnection()->prepare($RAW_QUERY);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    $camillaIntercambio = $this->getDoctrine()->getRepository(Camilla::class)->find($result[0]['id']);
                    $camillaIntercambio->setNumeroCamilla($camilla->getNumeroCamilla());
                    $camilla->setNumeroCamilla($result[0]['numero_camilla']);
                    $entityManager->persist($camilla);
                    $entityManager->flush();
                    $mensaje = 'Camilla Modificada con exito, se intercambiaron camillas entre la habitacion: '.$camillaIntercambio->getHabitacion()->getNumeroHabitacion().' de la sala: '.$camillaIntercambio->getHabitacion()->getSala()->getNombreSala().' con la habitacion: '.$camilla->getHabitacion()->getNumeroHabitacion().' de la sala :'.$camilla->getHabitacion()->getSala()->getNombreSala();
                    $this->addFlash('success',$mensaje);
                    return $this->redirectToRoute('camilla_index',['habitacion' => $habitacion->getId()]);
                    //FIN VERIFICACION PARA OBTENER LA CAMILLA QUE SE VA A TRANSFERIR A LA HABITACION ACTUAL
            }else{
                $this->addFlash('fail','Error, esta camilla no existe porfavor escoja una camilla existente.');
                return $this->render('camilla/edit.html.twig', [
                    'camilla' => $camilla,
                    'habitacion'    => $habitacion,
                    'editar'        => $editar,
                    'form' => $form->createView(),
                ]);
            }
            //FIN VALIDACION PARA COMPROBAR SI ESE NUMERO DE CAMILLA YA EXISTE
        }

        return $this->render('camilla/edit.html.twig', [
            'camilla' => $camilla,
            'habitacion'    => $habitacion,
            'editar'        => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{habitacion}", name="camilla_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Camilla $camilla, Habitacion $habitacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$camilla->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($camilla);
            $entityManager->flush();
        }
        $this->addFlash('success','Camilla eliminada con exito');
        return $this->redirectToRoute('camilla_index',['habitacion' => $habitacion->getId()]);
    }
}
