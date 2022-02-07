<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
