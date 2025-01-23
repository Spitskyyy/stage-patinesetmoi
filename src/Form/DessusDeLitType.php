<?php

namespace App\Form;

use App\Entity\DessusDeLit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DessusDeLitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('usetxt')
            ->add('length')
            ->add('width')
            ->add('lining')
            ->add('fabric')
            ->add('bedspread_finishes')
            ->add('time')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DessusDeLit::class,
        ]);
    }
}
