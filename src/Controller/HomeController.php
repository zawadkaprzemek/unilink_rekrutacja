<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @return Response
     */
    public function index(): Response
    {
        if($this->isGranted("ROLE_USER"))
        {
            return $this->redirectToRoute('list_my');
        }else{
            return $this->redirectToRoute('app_login');
        }
    }
}
