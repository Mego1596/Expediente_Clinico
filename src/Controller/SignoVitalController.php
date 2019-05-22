<?php

namespace App\Controller;

use App\Entity\SignoVital;
use App\Entity\Cita;
use App\Form\SignoVitalType;
use App\Repository\SignoVitalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/signo/vital")
 */
class SignoVitalController extends AbstractController
{
    /**
     * @Route("/{cita}", name="signo_vital_index", methods={"GET"})
     */
    public function index(SignoVitalRepository $signoVitalRepository, Security $AuthUser, Cita $cita): Response
    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT id, peso,temperatura,estatura,presion_arterial as presionArterial, ritmo_cardiaco as ritmoCardiaco FROM `signo_vital` WHERE cita_id =".$cita->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();

        return $this->render('signo_vital/index.html.twig', [
            'signos_vitales' => $result,
            'user'           => $AuthUser,
            'cita'           => $cita,
        ]);
    }

    /**
     * @Route("/new/{cita}", name="signo_vital_new", methods={"GET","POST"})
     */
    public function new(Request $request, Cita $cita): Response
    {
        //VALIDACION INICIAL PARA LA RUTA ES QUE SI LA CITA YA TIENE UN REGISTRO DE SIGNO VITAL BLOQUEARLA
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT * FROM `signo_vital` WHERE cita_id =".$cita->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        if($result == null){
            $editar = false;
            $signoVital = new SignoVital();
            $form = $this->createForm(SignoVitalType::class, $signoVital);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if($form["peso"]->getData() != ""){
                    if($form["temperatura"]->getData() != ""){
                        if($form["estatura"]->getData() != ""){
                            if($form["presionArterial"]->getData() != ""){
                                if($form["ritmoCardiaco"]->getData() != ""){
                                    //INICIO DE PROCESO DE DATOS
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $signoVital->setCita($cita);
                                    $entityManager->persist($signoVital);
                                    $entityManager->flush();
                                    //FIN DE PROCESO DE DATOS
                                }else{
                                   $this->addFlash('fail', 'Error, el ritmo cardiaco del paciente no puede estar vacio');
                                    return $this->render('signo_vital/new.html.twig', [
                                        'signo_vital' => $signoVital,
                                        'cita'           => $cita,
                                        'editar'         => $editar,
                                        'form' => $form->createView(),
                                    ]);
                                }
                            }else{
                               $this->addFlash('fail', 'Error, la presion arterial del paciente no puede estar vacia');
                                return $this->render('signo_vital/new.html.twig', [
                                    'signo_vital' => $signoVital,
                                    'cita'           => $cita,
                                    'editar'         => $editar,
                                    'form' => $form->createView(),
                                ]);
                            }
                        }else{
                            $this->addFlash('fail', 'Error, la estatura del paciente no puede estar vacia');
                            return $this->render('signo_vital/new.html.twig', [
                                'signo_vital' => $signoVital,
                                'cita'           => $cita,
                                'editar'         => $editar,
                                'form' => $form->createView(),
                            ]);
                        }
                    }else{
                        $this->addFlash('fail', 'Error, la temperatura del paciente no puede estar vacia');
                        return $this->render('signo_vital/new.html.twig', [
                            'signo_vital' => $signoVital,
                            'cita'           => $cita,
                            'editar'         => $editar,
                            'form' => $form->createView(),
                        ]);
                    }
                }else{
                    $this->addFlash('fail', 'Error, el peso del paciente no puede estar vacio');
                    return $this->render('signo_vital/new.html.twig', [
                        'signo_vital' => $signoVital,
                        'cita'           => $cita,
                        'editar'         => $editar,
                        'form' => $form->createView(),
                    ]);
                }
                $this->addFlash('success', 'Signos Vitales añadidos con exito');
                return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
            }
        }else{
            $this->addFlash('fail', 'Error, no se puede registrar signos vitales diferentes por favor modifique el registro ya ingresado');
            return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
        }

        return $this->render('signo_vital/new.html.twig', [
            'signo_vital' => $signoVital,
            'cita'           => $cita,
            'editar'         => $editar,
            'form' => $form->createView(),
        ]);


    }

    /**
     * @Route("/{id}/{cita}", name="signo_vital_show", methods={"GET"})
     */
    public function show(SignoVital $signoVital, Cita $cita): Response
    {
        return $this->render('signo_vital/show.html.twig', [
            'signo_vital' => $signoVital,
            'cita'           => $cita,
        ]);
    }

    /**
     * @Route("/{id}/{cita}/edit", name="signo_vital_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SignoVital $signoVital, Cita $cita): Response
    {
        $editar = true;
        $form = $this->createForm(SignoVitalType::class, $signoVital);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Signos Vitales modificados con exito');
            return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
        }

        return $this->render('signo_vital/edit.html.twig', [
            'signo_vital' => $signoVital,
            'cita'           => $cita,
            'editar'         => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{cita}", name="signo_vital_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SignoVital $signoVital, Cita $cita): Response
    {
        if ($this->isCsrfTokenValid('delete'.$signoVital->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($signoVital);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Signos Vitales eliminados con exito');
        return $this->redirectToRoute('signo_vital_index',['cita' => $cita->getId()]);
    }
}
