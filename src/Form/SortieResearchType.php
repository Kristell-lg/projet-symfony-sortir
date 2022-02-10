<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\SortieSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieResearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus',EntityType::class, [
                'class'=>Campus::class,
                'choice_label' => 'nom'
            ])
            ->add('recherche',TextType::class,[
                'label'=>'Contient',
                'required' => false
            ])
            ->add('apresLe',DateType::class,[
                'label'=>'Entre le',
                'html5'=>true,
                'widget'=>'single_text',
                'required' => false
            ])
            ->add('avantLe',DateType::class,[
                'label'=>'Et le',
                'html5'=>true,
                'widget'=>'single_text',
                'required' => false
            ])
            ->add('organisateur',CheckboxType::class,[
                'label'=>'Sorties dont je suis l\'organisateur/trice',
                'required' => false
                ])
            ->add('inscrit',CheckboxType::class,[
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('noInscrit',CheckboxType::class,[
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            ->add('passees',CheckboxType::class,[
                'label'=>'Sorties Passées',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieSearch::class,
        ]);
    }
}
