<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Form\AjoutParticipantSortiePriveeType;
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

    //***********************************Création de sortie ************************************************************

    public function create(
        EntityManagerInterface $entityManager,
        LieuxRepository        $lieuxRepository,
        CampusRepository       $campusRepository,
        EtatsRepository        $etatsRepository,
        ParticipantRepository  $participantRepository,
        Request                $request

    ): Response
    {


        try {
            $sortie = $_SESSION["NewSortie"];
            $sortie->setLieux($lieuxRepository->find($sortie->getLieux()->getId()));
        } catch (\Exception $e) {
            $sortie = new Sorties();
        }

        $sortieForm = $this->createForm(SortiesType::class, $sortie);
        $sortieForm->handleRequest($request);

        $lieu = new Lieux();
        $lieuForm = $this->createForm(LieuxType::class, $lieu);
        $lieuForm->handleRequest($request);

        //ajoute un lieu
        if ($sortieForm->get('lieu_btn')->isClicked()) {

            $_SESSION["NewSortie"] = $sortie;

            $sortieForm = $this->createForm(SortiesType::class, $sortie);
            $sortieForm->handleRequest($request);

            return $this->redirectToRoute('lieux_create');

        }


        //Traite le formulaire et envoie en base de données
        if ($sortieForm->get('creer_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $campusList = $campusRepository->findAll();
            foreach ($campusList as $c) {
                if ($c->getId() === $this->getUser()->getCampus()->getId()) {
                    $campus = $c;
                }
            }
            $sortie->setCampus($campus);

            $participantList = $participantRepository->findAll();
            foreach ($participantList as $p) {
                if ($p->getEmail() === $this->getUser()->getUserIdentifier()) {
                    $organisateur = $p;
                }
            }
            $sortie->setOrganisateur($organisateur);

            $sortie->setEtats($etatsRepository->find(1));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Evènement créé !');
            unset($_SESSION["NewSortie"]);
            return $this->redirectToRoute('main_home');
        }


        // Traite le formualaire et publie la sortie
        if ($sortieForm->get('publier_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $campusList = $campusRepository->findAll();
            foreach ($campusList as $c) {
                if ($c->getId() === $this->getUser()->getCampus()->getId()) {
                    $campus = $c;
                }
            }
            $sortie->setCampus($campus);

            $participantList = $participantRepository->findAll();
            foreach ($participantList as $p) {
                if ($p->getEmail() === $this->getUser()->getUserIdentifier()) {
                    $organisateur = $p;
                }
            }
            $sortie->setOrganisateur($organisateur);

            $sortie->setEtats($etatsRepository->find(2));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Evènement créé !');
            unset($_SESSION["NewSortie"]);
            return $this->redirectToRoute('main_home');
        }


        return $this->render('/sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieu' => ''
        ]);
    }

    /**
     * @Route("sortie/detail/{id}", name="sortie_details")
     */

    //********************************************Détail des sorties via le ID *****************************************
    public function details(
        int                   $id,
        Request               $request,
        SortiesRepository     $sortiesRepository,
        ParticipantRepository $participantRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        //Récupération de la sortie via son ID
        $sortie = $sortiesRepository->find($id);

        //Création du formulaire d'ajout de participant sortie privé
        $guestForm = $this->createForm(AjoutParticipantSortiePriveeType::class);

        $guestForm->handleRequest($request);

        if ($guestForm->isSubmitted()) {
            if ($sortie->getNbInscriptionMax() > count($sortie->getSortieParticipants()) && $sortie->getDateLimiteInscription() > new DateTime('NOW')) {

            $sortie->addSortieParticipants($participantRepository->findBy(["email" => $guestForm->get('guestEmail')->getData()])[0]);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash("success", "Invité ajouté !");
            $this->redirectToRoute("sortie_details", ["id" => $sortie->getId()]);
        } else {
            $this->addFlash("fail", "Invité n'a pas pu être ajouté !");
            $this->redirectToRoute("sortie_details", ["id" => $sortie->getId()]);
        }
    }

        return $this->render('sortie/detail.html.twig', [
            "sortie" => $sortie, "guestForm" => $guestForm->createView()
        ]);
    }

    /**
     * @Route("sortie/update/{id}", name="sortie_update")
     */
    //********************************Modification des sorties via le ID************************************************
    public function update(
        int                    $id,
        Request                $request,
        EtatsRepository        $etatsRepository,
        SortiesRepository      $sortiesRepository,
        LieuxRepository        $lieuxRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        //Recherche des sorties via le ID
        $sortie = $sortiesRepository->find($id);
        //Création du formulaire de modification
        $sortieForm = $this->createForm(SortieUpdateType::class, $sortie);
        $sortieForm->handleRequest($request);

        //Affiche le détail du lieu quand sélectionner
        if ($sortieForm->get('lieu_btn')->isClicked()) {
            $lieu = $lieuxRepository->find($sortieForm->getData()->getLieux()->getId());

            $sortieForm = $this->createForm(SortieUpdateType::class, $sortie);
            $sortieForm->handleRequest($request);

            return $this->render('/sortie/update.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'lieu' => $lieu
            ]);
        }

        //Enregistre la sortie si modifiée (ne change pas l'état dans la BDD)
        if ($sortieForm->get('creer_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie Modifiée !');

            return $this->redirectToRoute('main_home');
        }

        //Publie la sortie (change l'état en 2)
        if ($sortieForm->get('publier_btn')->isClicked() && $sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $sortie->setEtats($etatsRepository->find(2));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie Modifiée !');

            return $this->redirectToRoute('main_home');
        }

        //Publie la sortie (change l'état en 2)
        if ($sortieForm->get('supprimer_btn')->isClicked()) {

            $entityManager->remove($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie supprimée !');
            return $this->redirectToRoute('main_home');
        }


        return $this->render('/sortie/update.html.twig',
            [
                "sortie" => $sortie,
                "sortieForm" => $sortieForm->createView(),
                "lieu" => ""
            ]);
    }


    /**
     * @Route("sortie/cancel/{id}", name="sortie_cancel")
     */
    //************************Suppréssion de la sortie via l'ID*********************************************************
    public function cancel(
        int                    $id,
        EntityManagerInterface $entityManager,
        Request                $request,
        EtatsRepository        $etatsRepository,
        SortiesRepository      $sortiesRepository
    ): Response
    {
        //Récupération de la sortie via l'ID
        $sortie = $sortiesRepository->find($id);
        //Création du formulaire de suppréssion
        $motifForm = $this->createForm(MotifAnnulationType::class);

        $motifForm->handleRequest($request);

        if ($motifForm->get('enregistrer_btn')->isClicked() && $motifForm->isSubmitted() && $motifForm->isValid()) {

            //Récupération et stockage de la raison d'annulation dans la BDD
            $motifTab = $motifForm->getData();
            $motifFinal = $sortie->getInfosSortie() . "- Raison d'annulation : " . $motifTab["motif"];

            $sortie->setInfosSortie($motifFinal);

            //Changement de l'état à annuler
            $sortie->setEtats($etatsRepository->find(6));

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie Annulée !');

            return $this->redirectToRoute('main_home');

        }

        return $this->render('sortie/cancel.html.twig',
            [
                "sortie" => $sortie,
                "motifForm" => $motifForm->createView()
            ]);
    }


    /**
     * @Route("sortie/register/{idSortie}/{idUser}", name="sortie_register")
     */

    //*************Création des enregistrements de la sortie avec ID de la sortie et les ID Participants****************
    public function register(
        int                    $idSortie,
        int                    $idUser,
        ParticipantRepository  $participantRepository,
        SortiesRepository      $sortiesRepository,
        EntityManagerInterface $entityManager,
        Request                $request
    )
    {
        //Recherche de la sortie via son ID
        $sortie = $sortiesRepository->find($idSortie);
        //Recherche du participant via son ID
        $participant = $participantRepository->find($idUser);

        if ($sortie->getNbInscriptionMax() > count($sortie->getSortieParticipants()) && $sortie->getDateLimiteInscription() > new DateTime('NOW')) {
            $sortie->addSortieParticipants($participant);
            //Enregistrement du participant sur la sortie
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('main_home');
        } else {
            $this->addFlash('fail', 'L\'inscription a échoué car le nombre de place est déjà rempli ou la date de clôture est dépassée !');
            return $this->redirectToRoute('main_home');
        }
    }


    /**
     * @Route("sortie/unsubscribe/{idSortie}/{idUser}", name="sortie_unsubscribe")
     */

    //*********************Désinscription d'une sortie avec ID sortie et ID participant ********************************
    public function unsubscribe(
        int                    $idSortie,
        int                    $idUser,
        ParticipantRepository  $participantRepository,
        SortiesRepository      $sortiesRepository,
        EntityManagerInterface $entityManager,
        Request                $request
    )
    {
        //Recherche de la sortie via son ID
        $sortie = $sortiesRepository->find($idSortie);
        //Recherche du participant via son ID
        $participant = $participantRepository->find($idUser);

        if ($sortie->getDateLimiteInscription() > new DateTime('NOW')) {
            $sortie->removeSortie($participant);
            //Suppression du participant sur la sortie
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Désinscription réussie !');
            return $this->redirectToRoute('main_home');
        } else {
            $this->addFlash('fail', 'Vous ne pouvez vous désincrire après la fin des inscriptions !');
            return $this->redirectToRoute('main_home');
        }
    }
}
