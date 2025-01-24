<?php

namespace App\Form;

use App\Entity\FauteuilDagrement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FauteuilDagrementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('usetxt')
            ->add('width')
            ->add('depth')
            ->add('height')
            ->add('covering_or_complete_repair')
            ->add('materials')
            ->add('fabric')
            ->add('finishes')
            ->add('time')
            ->add('picture', FileType::class, [
                'label' => 'Image (fichier JPG ou PNG)',
                'mapped' => false,
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FauteuilDagrement::class,
        ]);
    }
}
