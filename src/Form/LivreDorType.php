<?php

namespace App\Form;

use App\Entity\LivreDor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LivreDorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('detail')
            ->add('pictures', FileType::class, [
                'label' => 'Upload des images',
                'multiple' => true, // Permet l'upload de plusieurs fichiers
                'mapped' => false, // Non lié directement à l'entité
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LivreDor::class,
        ]);
    }
}
