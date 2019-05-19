<?php

namespace App\Controller;

use App\Entity\Sala;
use App\Entity\Clinica;
use App\Form\SalaType;
use App\Repository\SalaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;
/**
 * @Route("/sala")
 */
class SalaController extends AbstractController
{
    /**
     * @Route("/{clinica}", name="sala_index", methods={"GET"})
     */
    public function index(SalaRepository $salaRepository,Clinica $clinica,Security $AuthUser): Response
    {
        $em = $this->getDoctrine()->getManager();
                $RAW_QUERY = "SELECT * FROM sala WHERE clinica_id = ".$clinica->getId().";";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('sala/index.html.twig', [
            'salas' => $result,
            'clinica' => $clinica,
            'user'  => $AuthUser,
        ]);
    }

    /**
     * @Route("/new/{clinica}", name="sala_new", methods={"GET","POST"})
     */
    public function new(Request $request,Clinica $clinica): Response
    {
        $editar=false;
        $sala = new Sala();
        $form = $this->createForm(SalaType::class, $sala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $sala->setClinica($clinica);
            $entityManager->persist($sala);
            $entityManager->flush();

            return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
        }

        return $this->render('sala/new.html.twig', [
            'sala' => $sala,
            'editar' =>$editar,
            'clinica'=>$clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{clinica}", name="sala_show", methods={"GET"})
     */
    public function show(Sala $sala,Clinica $clinica): Response
    {
        return $this->render('sala/show.html.twig', [
            'sala' => $sala,
            'clinica'=>$clinica,
        ]);
    }

    /**
     * @Route("/{id}/{clinica}/edit/", name="sala_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sala $sala,Clinica $clinica): Response
    {
        $editar=true;
        $form = $this->createForm(SalaType::class, $sala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
        }

        return $this->render('sala/edit.html.twig', [
            'sala' => $sala,
            'editar' =>$editar,
            'clinica'=>$clinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{clinica}", name="sala_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sala $sala,Clinica $clinica): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sala->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sala);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sala_index',array('clinica'=>$clinica->getId()));
    }
}
