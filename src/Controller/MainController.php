<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(): Response
    {
        return $this->render('main/index.html.twig', []);
    }

    /**
     * @Route("/", name="main_profil")
     */
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig', []);
    }

}
