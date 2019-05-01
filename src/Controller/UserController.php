<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Rol;
use App\Entity\Clinica;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'SELECT u.id as id,u.nombres as Nombres, u.apellidos as Apellidos,u.email as email, c.nombre_clinica as clinica, r.nombre_rol as rol FROM `user` as u,rol as r,clinica as c
WHERE u.hospital_id = c.id AND u.rol_id = r.id;;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController','users' => $result,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = new User();
        $roles = $this->getDoctrine()->getRepository(Rol::class)->findAll();
        $clinicas = $this->getDoctrine()->getRepository(Clinica::class)->findAll();
        $submittedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('create-item', $submittedToken)){
            $user->setEmail($request->request->get('email'));
            $user->setPassword(password_hash(substr(md5(microtime()),1,8),PASSWORD_DEFAULT,[15]));
            $rol = $this->getDoctrine()->getRepository(Rol::class)->find($request->request->get('role'));
            $clinica = $this->getDoctrine()->getRepository(Clinica::class)->find($request->request->get('clinica'));
            $user->setNombres($request->request->get('nombres'));
            $user->setApellidos($request->request->get('apellidos'));
            $user->setRol($rol);
            $user->setHospital($clinica);
            $em->persist($user);
            $em->flush();
        }
        /*$form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);*/
        return $this->render('user/new.html.twig', ['controller_name' => 'UserController','roles' => $roles,'clinicas' => $clinicas]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        /*$form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);*/
        return $this->render('user/edit.html.twig', ['controller_name' => 'UserController','user'=>$user]);

    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
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
