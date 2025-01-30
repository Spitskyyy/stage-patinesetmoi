<?php

namespace App\Form;

use App\Entity\AbatJour;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class AbatJourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', FileType::class, [ 'label' => 'Titre'])
            ->add('width', FileType::class, [ 'label' => 'Largeur'])
            ->add('depth', FileType::class, [ 'label' => 'Profondeur'])
            ->add('height', FileType::class, [ 'label' => 'Hauteur'])
            ->add('fabric', FileType::class, [ 'label' => 'Tissu(s) utilisé(s)'])
            ->add('materials', FileType::class, [ 'label' => 'Materiaux'])
            ->add('choice_of_strucure', FileType::class, [ 'label' => 'Titre'])
            ->add('lampshade_finishes', FileType::class, [ 'label' => 'Titre'])
            ->add('time', FileType::class, [ 'label' => 'Temps'])
            ->add('pictures', FileType::class, [
                'label' => 'Upload des images',
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
