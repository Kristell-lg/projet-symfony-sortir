<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    //************************************Création de participant ******************************************************
    public function register(CampusRepository $campusRepository,
                             Request $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             UserAuthenticatorInterface $userAuthenticator,
                             AppAuthenticator $authenticator,
                             EntityManagerInterface $entityManager
    ): Response
    {
        $user = new Participant();
        //Création du formulaire de création du participant
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encodage du mot d epasse
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
            );

            $user->setActif(true);

            //Récupération des images transmises
            $image = $form->get('images')->getData();
            //Génération du nom de fichier
            $fichier = md5(uniqid()).'.'.$image->guessExtension();

            if(filesize($image)<500000){
                //Copie du fichier dans le dossier uploads
                $image->move(
                    'uploads/',
                    $fichier
                );

                //Stockage de l'image en BDD(son nom)
                $img = new Images();
                $img->setName($fichier);
                $user->setImages($img);


            $entityManager->persist($user);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            }
            else {
                $this->addFlash('error', 'L\image n\'a pas pu être enregistrée car son poids dépasse 500Ko');
                return $this->redirectToRoute('main_home');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

