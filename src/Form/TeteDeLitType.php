<?php

namespace App\Form;

use App\Entity\TeteDeLit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeteDeLitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('picture')
            ->add('width')
            ->add('height')
            ->add('fabric')
            ->add('materials')
            ->add('support')
            ->add('headboard_finishes')
            ->add('time')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeteDeLit::class,
        ]);
    }
}
