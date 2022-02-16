<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\PropertySearch;
use App\Entity\Villes;
use App\Form\CampusType;
use App\Form\FilterResearchType;
use App\Form\TabFilterType;
use App\Form\VillesType;
use App\Repository\CampusRepository;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusVilleController extends AbstractController
{
    /**
     * @Route("/campus", name="campusville_campus")
     */
    public function campusListe(EntityManagerInterface $entityManager, CampusRepository $campusRepository, Request $request, ?campus $pCampus): Response
    {
        $recherche = new PropertySearch();
        try{
            $recherche->setRecherche($_SESSION["campusSearch"]->getRecherche());
        } catch( \Exception $e) {}
        $modif = false;

        $filterForm = $this->createForm(FilterResearchType::class,$recherche);
        $filterForm->handleRequest($request);

        if($filterForm->isSubmitted()||$recherche->getRecherche()!=''){
            if($recherche->getRecherche()!=''){
                $_SESSION["campusSearch"]=$recherche;
                $campus= $campusRepository->findWanted($recherche);
            }else{
                unset($_SESSION["campusSearch"]);
                $campus = $campusRepository->findAll();
            }
        } else{
            $campus = $campusRepository->findAll();
        }

        if($pCampus !== null){
            $campusNew= $pCampus;
            $modif=true;
        }
        else{
            $campusNew = new Campus();
        }
        $campusForm = $this->createForm(CampusType::class,$campusNew);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){
            $entityManager->persist($campusNew);
            $entityManager->flush();
            if($modif){
                $this->addFlash('success', 'Campus modifé !');
            } else{
                $this->addFlash('success', 'Campus ajouté !');
            }
            return $this->redirectToRoute('campusville_campus');
        }

        return $this->render('campus/campus.html.twig', [
            'campus'=>$campus,
            'pCampus'=>$pCampus,
            'campusForm'=>$campusForm->createView(),
            'filterForm'=>$filterForm->createView(),
            'modif'=>$modif
        ]);
    }

    /**
     * @Route("/villes", name="campusville_villes")
     */
    public function villesListe(EntityManagerInterface $entityManager, VillesRepository $villesRepository, Request $request,?Villes $ville): Response
    {
        $recherche = new PropertySearch();
        try{
            $recherche->setRecherche($_SESSION["VilleSearch"]->getRecherche());
        } catch( \Exception $e) {}
        $modif = false;

        $filterForm = $this->createForm(FilterResearchType::class,$recherche);
        $filterForm->handleRequest($request);

        if($filterForm->isSubmitted()||$recherche->getRecherche()!=''){
            if($recherche->getRecherche()!=''){
                $_SESSION["VilleSearch"]=$recherche;
                $villes= $villesRepository->findWanted($recherche);
            }else{
                unset($_SESSION["VilleSearch"]);
                $villes = $villesRepository->findAll();
            }
        } else{
            $villes= $villesRepository->findAll();
        }
        if($ville !== null){
            $villesNew= $ville;
            $modif=true;
        }
        else{
            $villesNew = new Villes();
        }

        $villesForm = $this->createForm(VillesType::class,$villesNew);
        $villesForm->handleRequest($request);

        if($villesForm->isSubmitted() && $villesForm->isValid()){
            $entityManager->persist($villesNew);
            $entityManager->flush();
            if($modif){
                $this->addFlash('success', 'Ville modifé !');
            } else{
                $this->addFlash('success', 'Ville ajouté !');
            }
            return $this->redirectToRoute('campusville_villes');
        }
        return $this->render('campus/villes.html.twig', [
            'villes'=>$villes,
            'ville'=>$ville,
            'villesForm'=>$villesForm->createView(),
            'filterForm'=>$filterForm->createView(),
            'modif'=>$modif
        ]);
    }
    /**
     * @Route("/villes/supprimer/{id}", name="campusville_supprimerville")
     */
    public function supprimerVille(int $id,EntityManagerInterface $entityManager, VillesRepository $villesRepository): Response
    {
        $ville = $villesRepository->find($id);
        $entityManager->remove($ville);
        $entityManager->flush();

        $this->addFlash('success', 'Ville supprimé !');
        return $this->redirectToRoute('campusville_villes');
    }
    /**
     * @Route("/campus/supprimer/{id}", name="campusville_supprimercampus")
     */
    public function supprimerCampus(int $id,EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->find($id);
        $entityManager->remove($campus);
        $entityManager->flush();

        $this->addFlash('success', 'Campus supprimé !');
        return $this->redirectToRoute('campusville_campus');
    }
    /**
     * @Route("/villes/modifier/{id}", name="campusville_modifierville")
     */
    public function modifierVille(int $id,EntityManagerInterface $entityManager, VillesRepository $villesRepository, Request $request): Response
    {
        $ville = $villesRepository->find($id);
        return $this->villesListe($entityManager,$villesRepository,$request,$ville);
    }

    /**
     * @Route("/campus/modifier/{id}", name="campusville_modifiercampus")
     */
    public function modifierCampus(int $id,EntityManagerInterface $entityManager, CampusRepository $campusRepository, Request $request): Response
    {
        $campus = $campusRepository->find($id);
        return $this->campusListe($entityManager,$campusRepository,$request,$campus);
    }
}
