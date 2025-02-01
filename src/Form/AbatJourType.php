<?php

namespace App\Form;

use App\Entity\AbatJour;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AbatJourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [ 'label' => 'Titre'])
            ->add('width', TextType::class, [ 'label' => 'Largeur'])
            ->add('depth', TextType::class, [ 'label' => 'Profondeur'])
            ->add('height', TextType::class, [ 'label' => 'Hauteur'])
            ->add('fabric', TextType::class, [ 'label' => 'Tissu(s) utilisé(s)'])
            ->add('materials', TextType::class, [ 'label' => 'Materiaux'])
            ->add('choice_of_strucure', TextType::class, [ 'label' => 'Choix de la structure'])
            ->add('lampshade_finishes', TextType::class, [ 'label' => 'Finitions abat jour  (bordures)'])
            ->add('time', TextType::class, [ 'label' => 'Temps de réalisation nécessaire'])
            ->add('pictures', FileType::class, [
                'label' => 'Téléchargement des images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'], 
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '20M', 
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                            'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, WebP).',
                        ])
                    ])
                ]
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AbatJour::class,
        ]);
    }
}
