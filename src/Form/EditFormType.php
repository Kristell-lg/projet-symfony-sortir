<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class EditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image',FileType::class,[
                'label' => 'Photo de profil'
            ])
            ->add('nom')
            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('email')
            ->add('telephone', TextType::class, ['label' => 'Téléphone'])
            ->add('password', RepeatedType::class, [
                'mapped'=>false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'Nouveau Mot de Passe'],
                'second_options' => ['label' => 'Répéter le nouveau Mot de Passe'],
            ])
            ->add('image',FileType::class,[
                'label'=>'Parcourir',
                'mapped'=>false,
                'required'=>false
            ])
            ->add('creer_btn', SubmitType::class, array(
                'label' => 'Modifier le profil'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }


}
