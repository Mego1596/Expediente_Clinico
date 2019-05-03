<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('base2.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/home", name="home")
     */
    public function home(Security $AuthUser)
    {   
        if($AuthUser->isGranted('ROLE_PERMISSION_ADMIN_USER')){
            return $this->render('home/Adminindex.html.twig', [
            'controller_name' => 'HomeController',
        ]);
        }else{
            return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
        }
    }

    
}
