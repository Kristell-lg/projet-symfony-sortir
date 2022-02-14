<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Form\LieuxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LieuxController extends AbstractController
{
    /**
     * @Route("/Lieux/create", name="lieux_create")
     */
    public function create(
        EntityManagerInterface $entityManager,
        Request $request
    ){
        $lieux = new Lieux();

        $LieuxForm = $this->createForm(LieuxType::class,$lieux);
        $LieuxForm->handleRequest($request);

        if($LieuxForm->isSubmitted() && $LieuxForm->isValid()) {
            $entityManager->persist($lieux);
            $entityManager->flush();
            $this->addFlash('success', 'Lieux ajouté !');

            return $this->redirectToRoute('sortie_create');
        }
        return $this->render('campus/Lieux.html.twig', [
            'lieuxForm'=>$LieuxForm->createView()
        ]);
    }
}