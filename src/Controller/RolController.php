<?php

namespace App\Controller;

use App\Entity\Rol;
use App\Entity\Permiso;
use App\Repository\RolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Security2;

/**
 * @Route("/rol")
 */
class RolController extends AbstractController
{
    /**
     * @Route("/", name="rol_index", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_INDEX_ROL')")
     */
    public function index(RolRepository $rolRepository, Security $AuthUser): Response
    {
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = "SELECT id, nombre_rol as nombreRol, descripcion FROM rol WHERE nombre_rol <> 'ROLE_SA'";
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('rol/index.html.twig', [
            'rols' => $result,
            'user' => $AuthUser,
        ]);
    }

    /**
     * @Route("/new", name="rol_new", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_NEW_ROL')")
     */
    public function new(Request $request): Response
    {
        $rol = new Rol();
        $form = $this->createFormBuilder($rol)
        ->add('nombreRol', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('descripcion', TextType::class,array('attr' => array('class' => 'form-control')))
        ->add('permisos', EntityType::class, array('class' => Permiso::class,'placeholder' => 'Seleccione los permisos',
            'choice_label' => 
                function (Permiso $permiso) {
                return $permiso->getDescripcion() . ' ' . strtolower($permiso->getNombreTabla());
                },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre_tabla', 'ASC');
            },
            'group_by' => 'nombre_tabla',
            'by_reference' => false,
            'multiple' => true,'expanded' => true,
            'attr' => array('class' => 'form-control')))
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rol->setNombreRol($form["nombreRol"]->getData());
            $rol->setDescripcion($form["descripcion"]->getData());
            foreach ($form["permisos"]->getData() as $permiso) {
                $rol->addPermiso($permiso);    
            }
            $entityManager->persist($rol);
            $entityManager->flush();

            $this->addFlash('success', 'Rol creado con exito');
            return $this->redirectToRoute('rol_index');
        }

        return $this->render('rol/new.html.twig', [
            'rol' => $rol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rol_show", methods={"GET"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_SHOW_ROL')")
     */
    public function show(Rol $rol): Response
    {
        return $this->render('rol/show.html.twig', [
            'rol' => $rol,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rol_edit", methods={"GET","POST"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_EDIT_ROL')")
     */
    public function edit(Request $request, Rol $rol): Response
    {

        $form = $this->createFormBuilder($rol)
        ->add('descripcion', TextType::class,array('attr' => array('class' => 'form-control')))
        ->add('permisos', EntityType::class, array('class' => Permiso::class,'placeholder' => 'Seleccione los permisos',
            'choice_label' => 
                function (Permiso $permiso) {
                return $permiso->getDescripcion() . ' ' . strtolower($permiso->getNombreTabla());
                },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre_tabla', 'ASC');
            },
            'group_by' => 'nombre_tabla',
            'by_reference' => false,
            'multiple' => true,'expanded' => true,
            'attr' => array('class' => 'form-control')))
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rol->setDescripcion($form["descripcion"]->getData());
            foreach ($form["permisos"]->getData() as $permiso) {
                $rol->addPermiso($permiso);    
            }
            $entityManager->persist($rol);
            $entityManager->flush();

            $this->addFlash('success', 'Rol modificado con exito');
            return $this->redirectToRoute('rol_index');
        }

        return $this->render('rol/edit.html.twig', [
            'rol' => $rol,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rol_delete", methods={"DELETE"})
     * @Security2("is_authenticated()")
     * @Security2("is_granted('ROLE_PERMISSION_DELETE_ROL')")
     */
    public function delete(Request $request, Rol $rol): Response
    {   
        if ($this->isCsrfTokenValid('delete'.$rol->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            if($rol->getNombreRol() != 'ROLE_DOCTOR' && $rol->getNombreRol()!= 'ROLE_SA'){
                $entityManager->remove($rol);
                $entityManager->flush();
            }else{
                $this->addFlash('fail', 'Rol Fundamental, no puede ser eliminado');
                return $this->redirectToRoute('rol_index');
            }
        }

        $this->addFlash('success', 'Rol eliminado con exito');
        return $this->redirectToRoute('rol_index');
    }
}
