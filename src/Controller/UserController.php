<?php

namespace App\Controller;

use App\Form\EditFormType;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->findAll();
        return $this->render('user/list.html.twig',
            ["participant" => $participant
            ]);
    }


    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function profil(int $id, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($id);

        return $this->render('/main/profil.html.twig', ["participant" => $participant]);
    }

    /**
     * @Route ("/edit/{id}" , name="edit")
     */

    public function edit(int                    $id,
                         ParticipantRepository  $participantRepository,
                         Request                $request,
                         EntityManagerInterface $entityManager
    )
    {
        $participant = $entityManager->getReference('App:Participant', $id);
        $form = $this->createForm(EditFormType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();
            $this->addFlash('Success', 'Le profil à bien été modifié!');

            return $this->redirectToRoute('main_home', [
                'id' => $participant->getId(),
            ]);

        }


        return $this->render('user/edit.html.twig', [
                'registrationForm' => $form->createView(), "participant" => $participant]

        );
    }


}
