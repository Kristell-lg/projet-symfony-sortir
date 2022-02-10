<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\SortieSearch;
use App\Form\SortieResearchType;
use App\Repository\CampusRepository;
use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(CampusRepository  $campusRepository,SortiesRepository $sortiesRepository, EtatsRepository $etatsRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $participe = false;

        $recherche= new  SortieSearch();
        $filterForm = $this->createForm(SortieResearchType::class,$recherche);
        $filterForm->handleRequest($request);
        $sortie = $sortiesRepository->findAll();

        $change = false;

        //Mettre à jour les clôture des évènements
        foreach ($sortie as $s){

            //ne peut être annulée
            if($s->getEtats()->getId()<5 && $s->getEtats()->getId()>1) {
                    //clôturée
                    if ($s->getDateLimiteInscription() < new \DateTime('NOW')) {
                        $s->setEtats($etatsRepository->find(3));
                        $change = true;

                        //en cours
                    } else if ($s->getDateLimiteInscription() === new \DateTime('NOW')) {
                        $s->setEtats($etatsRepository->find(4));
                        $change = true;

                        //passée
                    } else if ($s->getDateHeureDebut() < new \DateTime('NOW')) {
                        $s->setEtats($etatsRepository->find(5));
                        $change = true;
                    }
                }

            if($change==true){
                $entityManager->persist($s);
                $entityManager->flush();
                $change=false;
            }


        }
        return $this->render('main/index.html.twig', [
            "sortie" => $sortie,
            'participe' => $participe,
            'filterForm'=>$filterForm->createView()
            ]);
    }

}


