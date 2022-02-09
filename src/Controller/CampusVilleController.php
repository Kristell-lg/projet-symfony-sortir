<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Villes;
use App\Form\CampusType;
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
        $modif = false;
        $campus = $campusRepository->findAll();
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
            'campusForm'=>$campusForm->createView(),
            'modif'=>$modif
        ]);
    }

    /**
     * @Route("/villes", name="campusville_villes")
     */
    public function villesListe(EntityManagerInterface $entityManager, VillesRepository $villesRepository, Request $request,?Villes $ville): Response
    {
        $modif = false;
        $villes= $villesRepository->findAll();
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
            $entityManager->flush();if($modif){
                $this->addFlash('success', 'Ville modifé !');
            } else{
                $this->addFlash('success', 'Ville ajouté !');
            }
            return $this->redirectToRoute('campusville_villes');
        }
        return $this->render('campus/villes.html.twig', [
            'villes'=>$villes,
            'villesForm'=>$villesForm->createView(),
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
