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
            ->add('usagetxt')
            ->add('recouverture')
            ->add('materiaux')
            ->add('tissu')
            ->add('finition')
            ->add('temps')
            ->add('detail')
            ->add('largeur')
            ->add('profondeur')
            ->add('hauteur')
            ->add('image', FileType::class, [
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
