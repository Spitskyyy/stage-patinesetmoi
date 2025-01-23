<?php

namespace App\Form;

use App\Entity\AbatJour;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbatJourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('picture')
            ->add('width')
            ->add('depth')
            ->add('height')
            ->add('fabric')
            ->add('materials')
            ->add('choice_of_strucure')
            ->add('lampshade_finishes')
            ->add('time')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AbatJour::class,
        ]);
    }
}
