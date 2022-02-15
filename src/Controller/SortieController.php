<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Form\LieuxType;
use App\Form\MotifAnnulationType;
use App\Form\SortiesType;
use App\Form\SortieUpdateType;
use App\Repository\CampusRepository;
use App\Repository\EtatsRepository;
use App\Repository\LieuxRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortiesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(
        EntityManagerInterface $entityManager,
        LieuxRepository $lieuxRepository,
        CampusRepository $campusRepository,
        EtatsRepository $etatsRepository,
        ParticipantRepository $participantRepository,
        Request $request

    ): Response {


        try{
            $sortie = $_SESSION["NewSortie"];
            $sortie->setLieux($lieuxRepository->find($sortie->getLieux()->getId()));
            //dd($sortie->getLieux());
        } catch( \Exception $e) {
            $sortie = new Sorties();
        }

        //$sortie->setLieux(null);
        $sortieForm = $this->createForm(SortiesType::class,$sortie);
        $sortieForm->handleRequest($request);

        $lieu = new Lieux();
        $lieuForm = $this->createForm(LieuxType::class,$lieu);
        $lieuForm->handleRequest($request);

        //ajouter un lieu
        if($sortieForm->get('lieu_btn')->isClicked()){

            $_SESSION["NewSortie"]=$sortie;

            $sortieForm = $this->createForm(SortiesType::class,$sortie);
            $sortieForm->handleRequest($request);

            return $this->redirectToRoute('lieux_create');
            /*return $this->render('/sortie/create.html.twig', [
                'sortieForm'=>$sortieForm->createView(),
                'lieu'=>$lieu
            ]);*/
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
            unset($_SESSION["NewSortie"]);
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
            unset($_SESSION["NewSortie"]);
            return $this->redirectToRoute('main_home');
        }

        if($sortieForm->get('annuler_btn')->isClicked()){
            unset($_SESSION["NewSortie"]);
            return $this->redirectToRoute('main_home');
        }


        return $this->render('/sortie/create.html.twig', [
            'sortieForm'=>$sortieForm->createView(),
            'lieu'=>''
        ]);
    }

    /**
     * @Route("sortie/detail/{id}", name="sortie_details")
     */
    public function details(int $id, SortiesRepository $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->find($id);

        return $this->render('sortie/detail.html.twig', ["sortie"=>$sortie]);
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

            return $this->redirectToRoute('main_home');
        }

        //Publier la sortie (change l'état en 2)
        if($sortieForm->get('publier_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()){

            $sortie->setEtats($etatsRepository->find(2));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Sortie Modifiée !');

            return $this->redirectToRoute('main_home');
        }

        //Publier la sortie (change l'état en 2)
        if($sortieForm->get('supprimer_btn')->isClicked()){

            $entityManager->remove($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie supprimée !');
            return $this->redirectToRoute('main_home');
        }


        //Annuler et revenir à l'accueil
        if($sortieForm->get('annuler_btn')->isClicked()){
            return $this->redirectToRoute('main_home');
        }

        return $this->render('/sortie/update.html.twig',
            [
                "sortie"=>$sortie,
                "sortieForm"=>$sortieForm->createView(),
                "lieu"=>""
            ]);
    }


    /**
     * @Route("sortie/cancel/{id}", name="sortie_cancel")
     */
    public function cancel(int $id, EntityManagerInterface $entityManager, Request $request, EtatsRepository $etatsRepository, SortiesRepository  $sortiesRepository): Response
    {
        $sortie = $sortiesRepository->find($id);
        $motifForm = $this->createForm(MotifAnnulationType::class);

        $motifForm->handleRequest($request);

        if($motifForm->get('enregistrer_btn')->isClicked() && $motifForm->isSubmitted() && $motifForm->isValid()){

            //récupérer et stocker la raison d'annulation dans la BDD
            $motifTab = $motifForm->getData();
            $motifFinal = $sortie->getInfosSortie()."- Raison d'annulation : ".$motifTab["motif"];

            $sortie->setInfosSortie($motifFinal);

            //changer l'état à annuler
            $sortie->setEtats($etatsRepository->find(6));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Sortie Annulée !');

            return $this->redirectToRoute('main_home');

        }

            return $this->render('sortie/cancel.html.twig',
            [
                "sortie"=>$sortie,
                "motifForm"=>$motifForm->createView()
            ]);
    }


    /**
     * @Route("sortie/register/{idSortie}/{idUser}", name="sortie_register")
     */
    public function register(int $idSortie, int $idUser, ParticipantRepository $participantRepository, SortiesRepository $sortiesRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $sortie = $sortiesRepository->find($idSortie);
        $participant = $participantRepository->find($idUser);

        if($sortie->getNbInscriptionMax() > count($sortie->getSortieParticipants()) &&  $sortie->getDateLimiteInscription() > new DateTime('NOW'))
        {
            $sortie->addSortieParticipants($participant);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Inscription réussie !');
            return $this->redirectToRoute('main_home');
        }
        else{
            $this->addFlash('fail','L\'inscription a échoué car le nombre de place est déjà rempli ou la date de clôture est dépassée !');
            return $this->redirectToRoute('main_home');
        }
    }


    /**
     * @Route("sortie/unsubscribe/{idSortie}/{idUser}", name="sortie_unsubscribe")
     */
    public function unsubscribe(int $idSortie, int $idUser, ParticipantRepository $participantRepository, SortiesRepository $sortiesRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $sortie = $sortiesRepository->find($idSortie);
        $participant = $participantRepository->find($idUser);

        if($sortie->getDateLimiteInscription() > new DateTime('NOW'))
        {
            $sortie->removeSortie($participant);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Désinscription réussie !');
            return $this->redirectToRoute('main_home');
        }
        else{
            $this->addFlash('fail','Vous ne pouvez vous désincrire après la fin des inscriptions !');
            return $this->redirectToRoute('main_home');
        }
    }
}
