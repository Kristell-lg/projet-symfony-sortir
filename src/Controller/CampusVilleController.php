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
    public function campusListe(EntityManagerInterface $entityManager, CampusRepository $campusRepository, Request $request): Response
    {
        $campus = $campusRepository->findAll();

        $campusNew = new Campus();
        $campusForm = $this->createForm(CampusType::class,$campusNew);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){
            $entityManager->persist($campusNew);
            $entityManager->flush();
            $this->addFlash('success', 'Campus ajouté !');
            return $this->redirectToRoute('campusville_campus');
        }

        return $this->render('campus/campus.html.twig', [
            'campus'=>$campus,
            'campusForm'=>$campusForm->createView()
        ]);
    }

    /**
     * @Route("/villes", name="campusville_villes")
     */
    public function villesListe(EntityManagerInterface $entityManager, VillesRepository $villesRepository, Request $request): Response
    {
        $villes= $villesRepository->findAll();

        $villesNew = new Villes();
        $villesForm = $this->createForm(VillesType::class,$villesNew);
        $villesForm->handleRequest($request);

        if($villesForm->isSubmitted() && $villesForm->isValid()){
            $entityManager->persist($villesNew);
            $entityManager->flush();
            $this->addFlash('success', 'Ville ajoutée !');
            return $this->redirectToRoute('campusville_villes');
        }

        return $this->render('campus/villes.html.twig', [
            'villes'=>$villes,
            'villesForm'=>$villesForm->createView()
        ]);
    }
    /**
     * @Route("/villes/{id}", name="campusville_supprimerville")
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
     * @Route("/campus/{id}", name="campusville_supprimercampus")
     */
    public function supprimerCampus(int $id,EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->find($id);
        $entityManager->remove($campus);
        $entityManager->flush();

        $this->addFlash('success', 'Campus supprimé !');
        return $this->redirectToRoute('campusville_campus');
    }
}
