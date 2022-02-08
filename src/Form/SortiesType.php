<?php

namespace App\Form;


use App\Entity\Lieux;
use App\Entity\Sorties;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Nom de l\'évènement'
            ])
            ->add('dateHeureDebut', DateTimeType::class,[
                'label'=>'Date et Heure de début',
                'html5'=>true,
                'widget'=>'single_text'
            ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée (en minutes)'
            ])
            ->add('dateLimiteInscription',DateType::class,[
                'label'=>'Date limite d\'inscription',
                'html5'=>true,
                'widget'=>'single_text'
            ])
            ->add('nbInscriptionMax', IntegerType::class,[
                'label'=>'Nombre d\'inscritpions maximum'
            ])
            ->add('infosSortie', TextareaType::class,[
                'label'=>'Informations'
            ])
            ->add('lieux',EntityType::class,[
                'class'=>Lieux::class,
                'choice_label' => 'nom'
            ])
            //->add('sortie')
            //->add('orga')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
