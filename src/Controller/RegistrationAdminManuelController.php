<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegisterCSVType;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationAdminManuelController extends AbstractController
{
    /**
     * @Route("/registerAdmin", name="gestion_registerAdmin")
     */
    public function register(CampusRepository $campusRepository,
                             Request $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager
    ): Response
    {
        $user = new Participant();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
            );

            $user->setActif(true);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->addFlash('success', 'Utilisateur créé !');
            return $this->redirectToRoute('user_gestion');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registerAdminCSV", name="gestion_registerAdminCSV")
     */
    public function registerCSV(Request $request, CampusRepository $campusRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $formCSV = $this->createForm(RegisterCSVType::class);
        $formCSV->handleRequest($request);

        if ($formCSV->isSubmitted() && $formCSV->isValid()) {

            //on récup le fichier CSV
            $data = $formCSV->get('data')->getData();

            //Check si l'extension est bien un csv
            if ($data->getClientOriginalExtension() === "csv") {
                dump("success");

                $data->move(
                    'uploads/',
                    'data.csv'
                );


                $reader = Reader::createFromPath('uploads/data.csv', 'r');
                $reader->setHeaderOffset(0);

                $headersRule = ["nom","prenom","telephone","password","email","campus"];
                $headers = $reader->getHeader();

                if(empty(array_diff($headersRule,$headers))){

                //query sur les enregistrements du documents
                $records = $reader->getRecords();

                foreach ($records as $record) {
                    try{
                        $participant = new Participant();
                        $participant->setNom($record['nom']);
                        $participant->setPrenom($record['prenom']);
                        $participant->setTelephone($record['telephone']);
                        $participant->setEmail($record['email']);
                        $participant->setPassword(
                            $userPasswordHasher->hashPassword(
                                $participant,
                                $record['password']
                            )
                        );
                        $participant->setActif(true);
                        $participant->setRoles(["ROLE_USER"]);

                        $allCampus = $campusRepository->findAll();

                        foreach ($allCampus as $c){
                            if ($c->getNom() === $record['campus']){
                                $participant->setCampus($c);
                            }
                        }
                        $entityManager->persist($participant);
                    }
                    catch(\Exception $e){
                        $this->addFlash('fail', 'Une erreur est survenue: '.$e->getMessage().'!');
                        return $this->redirectToRoute('user_gestion');
                    }

                }

                $entityManager->flush();

                //supprimer le fichier quand fini
                unlink('uploads/data.csv');

                $this->addFlash('success', 'Utilisateurs créés !');
                return $this->redirectToRoute('user_gestion');

            }
                //les headers ne sont pas corrects
                else {
                    $this->addFlash('fail', 'La création n\'a pas pu aboutir - Veuillez vérifier les en-têtes du fichier  !');
                    return $this->redirectToRoute('user_gestion');
                }
                //le fichier n'est pas un CSV
            } else {
                $this->addFlash('fail', 'La création n\'a pas pu aboutir - Veuillez vérifier le type du fichier  !');
                return $this->redirectToRoute('user_gestion');
            }
        }

        return $this->render('user/gestionUserCSV.html.twig', [
            'formCSV' => $formCSV->createView()
        ]);
    }
}

