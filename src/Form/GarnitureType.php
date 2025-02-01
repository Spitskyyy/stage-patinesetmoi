<?php

namespace App\Form;

use App\Entity\Garniture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GarnitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [ 'label' => 'Titre'])
            ->add('detail',TextType::class, [ 'label' => 'Détail'])
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
            'data_class' => Garniture::class,
        ]);
    }
}
