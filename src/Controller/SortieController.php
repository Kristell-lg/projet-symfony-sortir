<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Entity\Villes;
use App\Form\LieuxType;
use App\Form\SortiesType;
use App\Form\VillesType;
use App\Repository\CampusRepository;
use App\Repository\EtatsRepository;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantRepository;
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
        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
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

        return $this->render('/sortie/create.html.twig', [
            'sortieForm'=>$sortieForm->createView(),
            'lieu'=>''
        ]);
    }
}
