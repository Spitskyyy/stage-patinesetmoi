<?php

namespace App\Form;

use App\Entity\FauteuilDagrement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FauteuilDagrementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('picture')
            ->add('usetxt')
            ->add('width')
            ->add('depth')
            ->add('height')
            ->add('covering_or_complete_repair')
            ->add('materials')
            ->add('fabric')
            ->add('finishes')
            ->add('time')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FauteuilDagrement::class,
        ]);
    }
}
