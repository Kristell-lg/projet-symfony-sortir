<?php

namespace App\Form;

use App\Entity\Sortie;
use phpDocumentor\Reflection\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom de la sortie:',
                'required' => true
            ])
            ->add('dateHeureDebut',DateTimeType::class,[
                'label'=>'Date et heure de la sortie:',
                'required' => true
            ])
            ->add('dateLimiteInscription',DateTimeType::class,[
                'label'=>'Date limite d\'inscription:',
                'required' => true
    ]       )
            ->add('nbInscriptionsMax',NumberType::class,[
                'label'=>'Nombre de places:'
            ])
            ->add('duree',NumberType::class,[
                'label'=>'Durée (Minutes):',
                'required' => true
            ])
            ->add('infosSortie',TextType::class,[
                'label'=>'Description et infos:'
            ])
            ->add('ville',EntityType::class,[
                'label'=>'Ville:',
                'required' => true
            ])
            ->add('lieu',EntityType::class,[
                'label'=>'Lieu:',
                'required' => true
            ])
            ->add('latitude',NumberType::class,[
                'label'=>'Latitude:',
                'required' => true
            ])
            ->add('longitude',NumberType::class,[
                'label'=>'Longitude:',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
