<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\EditFormType;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

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

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $participant->getImage();
            $fileName = md5(uniqid()) .'.'. $file->guessExtension();
           try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {}

            $participant->setImage($fileName);
            //$entityManager = $this->file($fileName);
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
    public function validation(AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator): Response
    {

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


    /*public function configureFields(string $pageName): iterable
    {
        return [
          TextField::new('nom'),
          TextField::new('prénom'),
          TextField::new('email'),
          NumberField::new('téléphone'),
          TextField::new('campus'),
          TextField::new('password'),
          TextField::new('imageFile')->setFormType(VichImageType::class)->onlywhenCreating,
          ImageField::new('file')->setBasePath('uploads/user/')->onlyonIndex(),
          SlugField::new('slug')->setTargetFieldName('nom')->hidenOnIndex(),
        ];
    }*/

    /**
     * @Route ("/gestion" , name="gestion")
     */
    public function gestion(ParticipantRepository $participantRepository)
    {
        $participantsList = $participantRepository->findAll();
        $isAdmin=false;
        return $this->render('user/gestionUser.html.twig', [
            'participantsList'=>$participantsList,
            'isAdmin'=>$isAdmin
        ]);
    }

    /**
     * @Route ("/gestion/desactiver/{id}" , name="gestion_desactivate")
     */
    public function gestionDesactivate(int $id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $participant = $participantRepository->find($id);
        $participant->setActif(false);

        $entityManager->persist($participant);
        $entityManager->flush();


        $this->addFlash("success","Désactivation réussie !");
        return $this->redirectToRoute('user_gestion');
    }

    /**
     * @Route ("/gestion/reactivate/{id}" , name="gestion_reactivate")
     */
    public function gestionReactivate(int $id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $participant = $participantRepository->find($id);
        $participant->setActif(true);

        $entityManager->persist($participant);
        $entityManager->flush();


        $this->addFlash("success","Réactivation réussie !");
        return $this->redirectToRoute('user_gestion');
    }


    /**
     * @Route ("/gestion/supprimer/{id}" , name="gestion_delete")
     */
    public function gestionDelete(int $id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $participant = $participantRepository->find($id);
        $entityManager->remove($participant);
        $entityManager->flush();

        $this->addFlash("success","Suppression réussie !");
        return $this->redirectToRoute('user_gestion');
    }

    /**
     * @Route ("/gestion/grantAdmin/{id}" , name="gestion_grantAdmin")
     */
    public function grantAdmin(int $id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $participant = $participantRepository->find($id);
        $participant->setRoles(["ROLE_USER","ROLE_ADMIN"]);

        $entityManager->persist($participant);
        $entityManager->flush();

        return $this->redirectToRoute('user_gestion');
    }

    /**
     * @Route ("/gestion/stripAdmin/{id}" , name="gestion_stripAdmin")
     */
    public function stripAdmin(int $id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $participant = $participantRepository->find($id);
        $participant->setRoles(["ROLE_USER"]);

        $entityManager->persist($participant);
        $entityManager->flush();

        return $this->redirectToRoute('user_gestion');
    }

}
