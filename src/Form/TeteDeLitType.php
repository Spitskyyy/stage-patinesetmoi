<?php

namespace App\Form;

use App\Entity\TeteDeLit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TeteDeLitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [ 'label' => 'Titre'])
            ->add('width',TextType::class, [ 'label' => 'Largeur '])
            ->add('height',TextType::class, [ 'label' => 'Hauteur '])
            ->add('fabric',TextType::class, [ 'label' => 'Tissu(s) utilisé(s)'])
            ->add('materials',TextType::class, [ 'label' => 'Materiaux utilisés'])
            ->add('support',TextType::class, [ 'label' => 'Support utilisé'])
            ->add('headboard_finishes',TextType::class, [ 'label' => 'TitFinitions de la tête de lit (bordures)'])
            ->add('time',TextType::class, [ 'label' => 'Temps de réalisation nécessaire'])
            ->add('pictures', FileType::class, [
                'label' => 'Téléchargement des images',
                'multiple' => true, // Permet l'upload de plusieurs fichiers
                'mapped' => false, // Non lié directement à l'entité
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeteDeLit::class,
        ]);
    }
}
