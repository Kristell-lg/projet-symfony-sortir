<?php

namespace App\Controller;

use App\Entity\Sortie;
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
        $sortie = $sortiesRepository->findAll();
        $etats = $etatsRepository->findAll();

        return $this->render('main/index.html.twig', ["sortie"=>$sortie, "etats"=>$etats]);
    }

    /**
     * @Route("/", name="main_profil")
     */
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig', []);
    }

    /**
     * @Route("/", name="main_seDeconnecter")
     */
    public function seDeconnecter(): Response
    {
        return $this->render('main/seDeconnecter.html.twig', []);
    }

    /**
     * @Route("/", name="main_campus")
     */
    public function campus(): Response
    {
        return $this->render('main/campus.html.twig', []);
    }

    /**
     * @Route("/", name="main_villes")
     */
    public function villes(): Response
    {
        return $this->render('main/villes.html.twig', []);
    }
}
