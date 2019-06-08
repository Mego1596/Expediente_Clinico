<?php

namespace App\Controller;

use App\Entity\Genero;
use App\Form\GeneroType;
use App\Repository\GeneroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/genero")
 */
class GeneroController extends AbstractController
{
    /**
     * @Route("/", name="genero_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_GENERO')")
     */
    public function index(GeneroRepository $generoRepository, Security $AuthUser): Response
    {
        return $this->render('genero/index.html.twig', [
            'generos' => $generoRepository->findAll(),
            'user' => $AuthUser,
        ]);
    }

    /**
     * @Route("/new", name="genero_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_GENERO')")
     */
    public function new(Request $request): Response
    {
        $editar =false;
        $genero = new Genero();
        $form = $this->createForm(GeneroType::class, $genero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($form["descripcion"]->getData() != ""){
                $entityManager->persist($genero);
                $entityManager->flush();
            }else{
                $this->addFlash('fail', 'El nombre del genero no puede estar vacio');
                return $this->render('genero/new.html.twig', [
                    'especialidad' => $genero,
                    'editar' => $editar,
                    'form' => $form->createView(),
                ]);
            }

            $this->addFlash('success', 'Genero agregado con exito');
            return $this->redirectToRoute('genero_index');
        }

        return $this->render('genero/new.html.twig', [
            'genero' => $genero,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="genero_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_GENERO')")
     */
    public function show(Genero $genero): Response
    {
        $form = $this->createFormBuilder($genero)
        ->add('descripcion',TextType::class, array('attr'=> array('class'=> 'form-control','disabled' => true)))
        ->getForm();
        return $this->render('genero/show.html.twig', [
            'genero' => $genero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="genero_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_GENERO')")
     */
    public function edit(Request $request, Genero $genero): Response
    {
        $editar=true;
        $form = $this->createForm(GeneroType::class, $genero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Genero modificado con exito');
            return $this->redirectToRoute('genero_index', [
                'id' => $genero->getId(),
            ]);
        }

        return $this->render('genero/edit.html.twig', [
            'genero' => $genero,
            'editar' => $editar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="genero_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("user.getIsActive()", statusCode=412, message="Su cuenta esta inactiva")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_GENERO')")
     */
    public function delete(Request $request, Genero $genero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genero->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($genero);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Genero eliminado con exito');
        return $this->redirectToRoute('genero_index');
    }
}
