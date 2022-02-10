<?php

namespace App\Controller;

use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(SortiesRepository $sortiesRepository, EtatsRepository $etatsRepository): Response
    {
        $participe = false;
        $sortie = $sortiesRepository->findAll();
        $etats = $etatsRepository->findAll();

        return $this->render('main/index.html.twig', ["sortie" => $sortie, "etats" => $etats, 'participe' => $participe]);
    }

    /**
     * @Route("/", name="main_profil")
     */
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig', []);
    }
}


