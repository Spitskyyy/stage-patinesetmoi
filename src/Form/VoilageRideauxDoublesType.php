<?php

namespace App\Form;

use App\Entity\VoilageRideauxDoubles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoilageRideauxDoublesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('picture')
            ->add('usetxt')
            ->add('width')
            ->add('height')
            ->add('lining')
            ->add('fabric')
            ->add('curtain_head_finishing')
            ->add('time')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VoilageRideauxDoubles::class,
        ]);
    }
}
