<?php

namespace App\Form;

use App\Entity\Banquette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BanquetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('image')
            ->add('finition')
            ->add('tissu')
            ->add('usagetxt')
            ->add('materiaux')
            ->add('temp')
            ->add('recouverture')
            ->add('largeur')
            ->add('profondeur')
            ->add('hauteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Banquette::class,
        ]);
    }
}
