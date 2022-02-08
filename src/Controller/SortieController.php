<?php

namespace App\Controller;

use App\Entity\Sorties;
use App\Form\SortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/", name="sortie_list")
     */
    public function list(): Response
    {
        return $this->render('/sortie/list.html.twig', [
        ]);
    }

    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(SortiesType::class,$sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            dd($sortie);
        }

        return $this->render('/sortie/create.html.twig', [
            'sortieForm'=>$sortieForm->createView()
        ]);
    }
}
