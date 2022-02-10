<?php

namespace App\Controller;

use App\Form\EditFormType;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use function Sodium\add;

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
       // $participant->password_verify('mon mot de passe', $participant->password);;

        $form = $this->createForm(EditFormType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())   {
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
    /**
     * @Route ("/validation" , name="validation")
     */
    public function validation(AuthenticationUtils $authenticationUtils,UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator): Response{

          //  return $this->redirectToRoute('user_validation');
        //}


       /* if (password_verify('participant.password', 'hash')) {
            echo 'Le mot de passe est valide !';
        } else {
            echo 'Le mot de passe est invalide.';
        }*/


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('user/Validation.html.twig', ['last_username' => $lastUsername, 'error' => $error,]);

    }


}
