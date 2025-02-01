<?php

namespace App\Form;

use App\Entity\DessusDeLit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DessusDeLitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [ 'label' => 'Titre'])
            ->add('usetxt',TextType::class, [ 'label' => 'Usage/Destination'])
            ->add('length',TextType::class, [ 'label' => 'Longueur '])
            ->add('width',TextType::class, [ 'label' => 'Largeur '])
            ->add('lining',TextType::class, [ 'label' => 'Doublure utilisée'])
            ->add('fabric',TextType::class, [ 'label' => 'Tissu(s) utilisé(s)'])
            ->add('bedspread_finishes',TextType::class, [ 'label' => 'Finitions du dessus de lit (bordures, piquage ou sertissage des motifs)'])
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
            'data_class' => DessusDeLit::class,
        ]);
    }
}
