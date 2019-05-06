<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Clinica;
use App\Entity\Rol;
use App\Entity\Especialidad;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @Security2("has_role('ROLE_PERMISSION_INDEX_USER')")
     */
    public function index(UserRepository $userRepository, Security $AuthUser): Response
    {
        $em = $this->getDoctrine()->getManager();
        if($AuthUser->getUser()->getRol()->getNombreRol() == 'ROLE_SA'){
            $RAW_QUERY = 'SELECT u.id as id,u.nombres as Nombres, u.apellidos as Apellidos,u.email as email, c.nombre_clinica as clinica, r.nombre_rol as rol FROM `user` as u,rol as r,clinica as c
                WHERE u.clinica_id = c.id AND u.rol_id = r.id AND r.nombre_rol <> "ROLE_PACIENTE;"';
        }else{
            $RAW_QUERY = 'SELECT u.id as id,u.nombres as Nombres, u.apellidos as Apellidos,u.email as email, c.nombre_clinica as clinica, r.nombre_rol as rol FROM `user` as u,rol as r,clinica as c
                WHERE u.clinica_id = c.id AND u.rol_id = r.id AND r.nombre_rol <> "ROLE_SA" AND r.nombre_rol <> "ROLE_PACIENTE";';
        }
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController','users' => $result
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @Security2("has_role('ROLE_PERMISSION_NEW_USER')")
     */
    public function new(Request $request): Response
    {
        
        $user = new User();
        $form = $this->createFormBuilder($user)
        ->add('nombres', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('apellidos', TextType::class,array('attr' => array('class' => 'form-control')))
        ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
        ->add('password', PasswordType::class, array('attr' => array('class' => 'form-control')))
        ->add('clinica', EntityType::class, array('class' => Clinica::class,'placeholder' => 'Seleccione una clinica','choice_label' => 'nombreClinica','attr' => array('class' => 'form-control')))
        ->add('rol', EntityType::class, array('class' => Rol::class,'placeholder' => 'Seleccione un rol','choice_label' => 'descripcion',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.nombreRol != :val')
                    ->setParameter('val', "ROLE_PACIENTE");
            },'attr' => array('class' => 'form-control')))
        ->add('usuario_especialidades', EntityType::class, array('class' => Especialidad::class,'placeholder' => 'Seleccione las especialidades','choice_label' => 'nombreEspecialidad','multiple' => true,'expanded' => true,
            'attr' => array('class' => 'form-control')))
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getEntityManager();
            $user->setEmail($form["email"]->getData());
            $user->setPassword(password_hash($form["password"]->getData(),PASSWORD_DEFAULT,[15]));
            $user->setNombres($form["nombres"]->getData());
            $user->setApellidos($form["apellidos"]->getData());
             $user->setRol($form["rol"]->getData());
            $user->setClinica($form["clinica"]->getData());
            foreach ($form["usuario_especialidades"]->getData() as $especialidad) {
                $user->addUsuarioEspecialidades($especialidad);    
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Usuario creado con exito');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @Security2("has_role('ROLE_PERMISSION_SHOW_USER')")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/doctor/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @Security2("has_role('ROLE_PERMISSION_EDIT_USER')")
     */
    public function edit(Request $request, User $user): Response
    {
        //////////////////////////////// ZONA DE CREACION DE FORMULARIO ///////////////////////////
        $form = $this->createFormBuilder($user)
        ->add('nombres', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('apellidos', TextType::class,array('attr' => array('class' => 'form-control')))
        ->add('email', EmailType::class, array('attr' => array('class' => 'form-control')), 'disabled' => true)
        ->add('clinica', EntityType::class, array('class' => Clinica::class,'placeholder' => 'Seleccione una clinica','choice_label' => 'nombreClinica','attr' => array('class' => 'form-control')))
        ->add('rol', EntityType::class, array('class' => Rol::class,'placeholder' => 'Seleccione un rol','choice_label' => 'nombreRol',
            'attr' => array('class' => 'form-control')))
        ->add('nuevo_password', PasswordType::class, array('attr' => array('class' => 'form-control'), 'required' => false, 'mapped' => false))
        ->add('repetir_nuevo_password', PasswordType::class, array('attr' => array('class' => 'form-control'), 'required' => false, 'mapped' => false))
        ->add('usuario_especialidades', EntityType::class, array('class' => Especialidad::class,'placeholder' => 'Seleccione las especialidades','choice_label' => 'nombreEspecialidad','multiple' => true,'expanded' => true,
            'attr' => array('class' => 'form-control')))
        ->add('guardar', SubmitType::class, array('attr' => array('class' => 'btn btn-outline-success')))
        ->getForm();

        $form->handleRequest($request);

        /////////////////////////////// ZONA DE PROCESAMIENTO /////////////////////////////////////
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $agregar_especialidades = true;
            $exito = true;
            $pwd = $user->getPassword();

            if (empty($form['nuevo_password']->getData()) && empty($form['repetir_nuevo_password']->getData()))
            {   
            }
            else
            {
                if ($form['nuevo_password']->getData() == $form['repetir_nuevo_password']->getData())
                {
                    $pwd = password_hash($form["nuevo_password"]->getData(),PASSWORD_DEFAULT,[15]);
                }
                else
                {
                    $this->addFlash('fail', 'Contraseñas deben coincidir');
                    $exito = false;
                }
            }

            if ($exito)
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $rol = new Rol();
                $rol = $this->getDoctrine()->getRepository(Rol::class)->findOneById($form['rol']->getData());

                // Caso en que el ROL era antes de doctor y ahora sera otro rol
                if ($rol->getId() != 2)
                {
                    $agregar_especialidades = false;
                }
                
                $user->setPassword($pwd);
                $user->setEmail($form["email"]->getData());
                $user->setNombres($form["nombres"]->getData());
                $user->setApellidos($form["apellidos"]->getData());
                $user->setRol($rol);
                $user->setClinica($form["clinica"]->getData());

                if ($agregar_especialidades)
                {
                    foreach ($form["usuario_especialidades"]->getData() as $especialidad) {
                        $user->addUsuarioEspecialidades($especialidad);    
                    }
                }
                else
                {
                    $user->removeEspecialidades();
                }

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Usuario modificado con exito');
                return $this->redirectToRoute('user_index');
            }            
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @Security2("has_role('ROLE_PERMISSION_DELETE_USER')",statusCode=404, message="")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}



