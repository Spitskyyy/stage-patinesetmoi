<?php

namespace App\Form;

use App\Entity\Banquette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BanquetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [ 'label' => 'Titre'])
            ->add('usetxt', TextType::class, [ 'label' => 'Usage/Déstination'])
            ->add('width',TextType::class, [ 'label' => 'Longueur'])
            ->add('depth',TextType::class, [ 'label' => 'Profondeur'])
            ->add('height',TextType::class, [ 'label' => 'Hauteur'])
            ->add('covering_or_complete_repair',TextType::class, [ 'label' => 'Recouverture ou réfection complète'])
            ->add('materials',TextType::class, [ 'label' => 'Materiaux utilisés'])
            ->add('fabric',TextType::class, [ 'label' => 'Tissu(s) utilisés'])
            ->add('finishes',TextType::class, [ 'label' => 'Finitions'])
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
            'data_class' => Banquette::class,
        ]);
    }
}
