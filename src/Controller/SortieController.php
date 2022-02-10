<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Entity\Villes;
use App\Form\LieuxType;
use App\Form\SortiesType;
use App\Form\SortieUpdateType;
use App\Form\VillesType;
use App\Repository\CampusRepository;
use App\Repository\EtatsRepository;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortiesRepository;
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
    public function list(SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->findAll();
        return $this->render('sortie/list.html.twig', [
            'sortie'=>$sortie,
        ]);
    }

    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request, LieuxRepository $lieuxRepository, CampusRepository $campusRepository, EtatsRepository $etatsRepository,ParticipantRepository $participantRepository, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(SortiesType::class,$sortie);
        $sortieForm->handleRequest($request);

        $lieu = new Lieux();
        $lieuForm = $this->createForm(LieuxType::class,$lieu);
        $lieuForm->handleRequest($request);

        //Afficher le détail du lieu quand sélectionner
        if($sortieForm->get('lieu_btn')->isClicked()){
            $lieu = $lieuxRepository->find($sortieForm->getData()->getLieux()->getId());

            $sortieForm = $this->createForm(SortiesType::class,$sortie);
            $sortieForm->handleRequest($request);

            return $this->render('/sortie/create.html.twig', [
                'sortieForm'=>$sortieForm->createView(),
                'lieu'=>$lieu
            ]);
        }


        //Traiter le formulaire et envoyer en base de données
        if($sortieForm->get('creer_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()){
            $campusList = $campusRepository->findAll();
            foreach ($campusList as $c){
                if ($c->getId()===$this->getUser()->getCampus()->getId()){
                    $campus =$c;
                }
            }
            $sortie->setCampus($campus);

            $participantList = $participantRepository->findAll();
            foreach ($participantList as $p){
                if ($p->getEmail()===$this->getUser()->getUserIdentifier()){
                    $organisateur =$p;
                }
            }
            $sortie->setOrganisateur($organisateur);

            $sortie->setEtats($etatsRepository->find(1));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Evènement créé !');
            return $this->redirectToRoute('main_home');
        }


        // Traiter le formualire et Publier la sortie
        if($sortieForm->get('publier_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()){
            $campusList = $campusRepository->findAll();
            foreach ($campusList as $c){
                if ($c->getId()===$this->getUser()->getCampus()->getId()){
                    $campus =$c;
                }
            }
            $sortie->setCampus($campus);

            $participantList = $participantRepository->findAll();
            foreach ($participantList as $p){
                if ($p->getEmail()===$this->getUser()->getUserIdentifier()){
                    $organisateur =$p;
                }
            }
            $sortie->setOrganisateur($organisateur);

            $sortie->setEtats($etatsRepository->find(2));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Evènement créé !');
            return $this->redirectToRoute('main_home');
        }

        if($sortieForm->get('annuler_btn')->isClicked()){
            return $this->redirectToRoute('main_home');
        }


        return $this->render('/sortie/create.html.twig', [
            'sortieForm'=>$sortieForm->createView(),
            'lieu'=>''
        ]);
    }

    /**
     * @Route("sortie/list/{id}", name="sortie_details")
     */
    public function details(int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->find($id);


        return $this->render('/sortie/list.html.twig', ["sortie"=>$sortie]);
    }

    /**
     * @Route("sortie/update/{id}", name="sortie_update")
     */
    public function update(int $id, Request $request, EtatsRepository $etatsRepository, SortiesRepository $sortiesRepository, LieuxRepository $lieuxRepository, EntityManagerInterface $entityManager): Response
    {
        $sortie = $sortiesRepository->find($id);
        $sortieForm = $this->createForm(SortieUpdateType::class,$sortie);
        $sortieForm->handleRequest($request);

        //Afficher le détail du lieu quand sélectionner
        if($sortieForm->get('lieu_btn')->isClicked()){
            $lieu = $lieuxRepository->find($sortieForm->getData()->getLieux()->getId());

            $sortieForm = $this->createForm(SortieUpdateType::class,$sortie);
            $sortieForm->handleRequest($request);

            return $this->render('/sortie/update.html.twig', [
                'sortieForm'=>$sortieForm->createView(),
                'lieu'=>$lieu
            ]);
        }

        //Enregistrer la sortie si modifier (ne change pas l'état dans la BDD)
        if($sortieForm->get('creer_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()){

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Sortie Modifiée !');

            return $this->render('/sortie/list.html.twig',
                [
                    "sortie"=>$sortie,
                ]);
        }

        //Publier la sortie (change l'état en 2)
        if($sortieForm->get('publier_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()){

            $sortie->setEtats($etatsRepository->find(2));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Sortie Modifiée !');

            return $this->render('/sortie/list.html.twig',
                [
                    "sortie"=>$sortie,
                ]);
        }

        return $this->render('/sortie/update.html.twig',
            [
                "sortie"=>$sortie,
                "sortieForm"=>$sortieForm->createView(),
                "lieu"=>""
            ]);
    }



}
